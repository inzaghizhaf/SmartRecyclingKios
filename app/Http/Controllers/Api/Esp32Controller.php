<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TrashEvent;
use App\Models\DailyHistory;
use App\Models\Machine;
use App\Models\CarbonCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Esp32Controller extends Controller
{
    /**
     * Menerima pembaruan GPS dari ESP32.
     * Contoh payload: {"device_id":"SRK-001-GPS","latitude":-7.5755,"longitude":110.8243}
     */
    public function location(Request $request)
    {
        $data = $request->validate([
            'device_id' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'name' => ['nullable', 'string', 'max:100'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'satellites' => ['nullable', 'integer', 'between:0,99'],
            'signal_strength' => ['nullable', 'integer', 'between:0,100'],
            'status' => ['nullable', 'in:online,maintenance'],
        ]);

        $machine = Machine::firstOrNew(['device_id' => $data['device_id']]);
        $machine->fill([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'last_seen' => now(),
        ]);

        foreach (['name', 'location_name', 'satellites', 'signal_strength', 'status'] as $field) {
            if (array_key_exists($field, $data)) {
                $machine->{$field} = $data[$field];
            }
        }

        if (!$machine->exists && !isset($data['status'])) {
            $machine->status = 'online';
        }

        $machine->save();

        return response()->json([
            'message' => 'Lokasi mesin berhasil diperbarui',
            'machine_id' => $machine->id,
            'last_seen' => $machine->last_seen,
        ]);
    }

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

        $carbonFactor = CarbonCalculator::where('waste_type', $jenis === 'plastic' ? 'Botol Plastik' : 'Kaleng Aluminium')
            ->value('co2_factor') ?? ($jenis === 'plastic' ? 0.09 : 0.08);

        DB::transaction(function () use ($user, $jenis, $poin, $nilai_rp, $carbonFactor, $data) {
        TrashEvent::create([
            'user_id' => $user->id,
            'jenis_sampah' => $jenis,
            'poin' => $poin,
            'nilai_rp' => $nilai_rp,
            'carbon_footprint' => $carbonFactor,
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
            'carbon_footprint' => $carbonFactor,
        ], 201);
    }
}
