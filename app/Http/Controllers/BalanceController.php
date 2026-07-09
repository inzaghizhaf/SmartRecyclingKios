<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function withdraw(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->jumlah;

        // 🔴 Saldo di bawah 10.000
        if ($user->balance < 10000) {
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'withdraw',
                'status' => 'failed',
            ]);
            return back()->with('error', 'Saldo belum mencapai minimal Rp10.000 untuk melakukan penarikan.');
        }

        // 🔴 Saldo tidak cukup
        if ($user->balance < $amount) {
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'withdraw',
                'status' => 'failed',
            ]);
            return back()->with('error', 'Saldo belum cukup untuk melakukan penarikan.');
        }

        // ✅ Penarikan berhasil
        $user->balance -= $amount;
        $conversionRate = 50;
        // Hitung poin yang perlu dikurangi sesuai konversi 1 poin = Rp50
        $pointsToDeduct = $amount / $conversionRate;
        
        // Kurangi poin dan pastikan tidak negatif
        $user->points -= $pointsToDeduct;
        $user->points = max(0, $user->points);
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'withdraw',
            'status' => 'success',
        ]);

        return back()->with('success', 'Penarikan saldo berhasil dilakukan. Saldo akan dikirim dalam 1x24 jam');
    }
}
