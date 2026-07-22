<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'metode' => 'required|string',
            'nomor' => 'required|string|max:30',
            'jumlah' => 'required|numeric|min:10000',
        ]);

        $user = Auth::user();

        if ($user->balance < $request->jumlah) {
            return back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        Withdrawal::create([
            'user_id' => Auth::id(),
            'metode' => $request->metode,
            'nomor' => $request->nomor,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan penarikan berhasil dikirim dan menunggu ACC admin.');
    }
}
