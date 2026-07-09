<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrashEvent;
use App\Models\DailyHistory;
use Carbon\Carbon;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->fresh();

        $events = TrashEvent::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Ambil history hari ini (bukan yang kemarin)
        $history = DailyHistory::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today()) // penting: filter tanggal hari ini
            ->first();

        return view('dashboard', compact('user', 'events', 'history'));
    }

    public function tarikSaldo(Request $request)
    {
    $request->validate([
        'jumlah' => 'required|numeric|min:1000',
    ]);

    $user = Auth::user();

    if ($user->balance < $request->jumlah) {
        return back()->with('error', 'Saldo tidak cukup!');
    }

    // Kurangi saldo
    $user->balance -= $request->jumlah;
    $user->save();

    return back()->with('success', 'Saldo berhasil ditarik!');
}

}
