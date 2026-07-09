<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TrashEvent;
use App\Models\DailyHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Esp32Controller extends Controller
{
    public function input(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'sensor_proximity' => 'required|boolean',
            'sensor_ultrasonic' => 'required|boolean',
        ]);

        $user = User::find($data['user_id']);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $jenis = null;
        $jenis_kode = 0; // untuk simpan di database
        $poin = 0;
        $nilai_rp = 0;

        // logika deteksi
        if ($data['sensor_proximity'] && $data['sensor_ultrasonic']) {
            $jenis = 'can';
            $jenis_kode = 2;
            $poin = 2;
            $nilai_rp = 100;
        } elseif (!$data['sensor_proximity'] && $data['sensor_ultrasonic']) {
            $jenis = 'plastic';
            $jenis_kode = 1;
            $poin = 1;
            $nilai_rp = 50;
        } else {
            return response()->json(['message' => 'Tidak ada sampah terdeteksi']);
        }

        DB::transaction(function () use ($user, $jenis, $poin, $nilai_rp, $data) {
        TrashEvent::create([
            'user_id' => $user->id,
            'jenis_sampah' => $jenis,
            'poin' => $poin,
            'nilai_rp' => $nilai_rp,
            'sensor_proximity' => $data['sensor_proximity'],
            'sensor_ultrasonic' => $data['sensor_ultrasonic'],
        ]);

        $user->points += $poin;
        $user->balance += $nilai_rp;
        $user->save();

        // 🔥 Ganti bagian updateOrCreate dengan ini:
        $today = Carbon::today();
        $history = DailyHistory::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($history) {
            $history->total_sampah += 1;
            $history->total_poin += $poin;
            $history->total_rp += $nilai_rp;
            $history->save();
        } else {
            DailyHistory::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'total_sampah' => 1,
                'total_poin' => $poin,
                'total_rp' => $nilai_rp,
            ]);
        }
    });


        return response()->json([
            'message' => 'Data sampah tersimpan',
            'jenis_sampah' => $jenis, // tambahkan biar tahu yang terdeteksi apa
            'user_points' => $user->points,
            'user_balance' => $user->balance,
        ], 201);
    }
}
