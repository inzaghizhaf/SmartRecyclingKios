<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        if(auth()->user()->role==='admin'){
            $transactions = \App\Models\Transaction::latest()->paginate(20);
        } else {
            $transactions = auth()->user()->transactions()->latest()->paginate(20);
        }
        return view('transactions.index', compact('transactions'));
    }

    public function exchange(Request $req)
    {
        $req->validate([
            'points' => 'required|integer|min:1',
        ]);
        $user = auth()->user();
        $points = $req->input('points');
        if($user->points < $points) return back()->withErrors('Poin tidak cukup');

        // ambil rate (misal fixed)
        $rate = 0.025; // misal 1 poin = Rp 25 (ubah sesuai Price model)
        $amount = $points * ($rate*1000)/1000; // contoh
        // update user
        $user->points -= $points;
        $user->balance += $amount;
        $user->save();

        // catat transaksi
        \App\Models\Transaction::create([
            'user_id'=>$user->id,
            'type'=>'exchange',
            'points'=>$points,
            'amount'=>$amount,
            'note'=>'Konversi poin ke saldo',
        ]);
        return back()->with('success','Penukaran berhasil');
    }


}
