<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = in_array(auth()->user()->role, ['admin', 'super_admin'], true);

        // ── Logika lama untuk user biasa tetap dipertahankan ──
        if (!$isAdmin) {
            $transactions = auth()->user()->transactions()->latest()->paginate(20);
            return view('admin.transactions.index', compact('transactions', 'isAdmin'));
        }

        // ══════════════════════════════════════════════
        // TAMBAHAN: Data Transaksi gabungan untuk Admin/Super Admin
        // ══════════════════════════════════════════════
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $jenis          = $request->input('jenis', 'semua');
        $status         = $request->input('status', 'semua');
        $cari           = $request->input('cari');

        // Deposit (dari tabel transactions, type = deposit)
        $deposits = Transaction::with('user')
            ->where('type', 'deposit')
            ->when($tanggalMulai, fn($q) => $q->whereDate('created_at', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn($q) => $q->whereDate('created_at', '<=', $tanggalSelesai))
            ->when($cari, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('nama_lengkap', 'like', "%{$cari}%")))
            ->get()
            ->map(function ($t) {
                return (object) [
                    'nama'    => $t->user->nama_lengkap ?? '-',
                    'email'   => $t->user->email ?? '-',
                    'jenis'   => 'Deposit',
                    'poin'    => '+' . $t->points,
                    'nominal' => $t->amount,
                    'metode'  => 'Mesin',
                    'status'  => 'Berhasil',
                    'tanggal' => $t->created_at,
                    'id'      => 'D-' . $t->id,
                ];
            });

        // Withdraw (dari tabel withdrawals)
        $withdrawals = Withdrawal::with('user')
            ->when($tanggalMulai, fn($q) => $q->whereDate('created_at', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn($q) => $q->whereDate('created_at', '<=', $tanggalSelesai))
            ->when($cari, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('nama_lengkap', 'like', "%{$cari}%")))
            ->get()
            ->map(function ($w) {
                $statusLabel = match ($w->status) {
                    'approved' => 'Berhasil',
                    'rejected' => 'Ditolak',
                    default    => 'Pending',
                };
                return (object) [
                    'nama'    => $w->user->nama_lengkap ?? '-',
                    'email'   => $w->user->email ?? '-',
                    'jenis'   => 'Withdraw',
                    'poin'    => '-' . floor($w->jumlah / 50),
                    'nominal' => $w->jumlah,
                    'metode'  => $w->metode,
                    'status'  => $statusLabel,
                    'tanggal' => $w->created_at,
                    'id'      => 'W-' . $w->id,
                ];
            });

        // Statistik ringkasan (tidak ikut terpengaruh filter jenis/status, hanya tanggal & pencarian)
        $stats = [
            'total_deposit'    => $deposits->count(),
            'total_deposit_rp' => $deposits->sum('nominal'),
            'total_withdraw'   => $withdrawals->count(),
            'total_withdraw_rp'=> $withdrawals->sum('nominal'),
            'pending'          => $withdrawals->where('status', 'Pending')->count(),
            'total_transaksi'  => $deposits->count() + $withdrawals->count(),
        ];

        // Gabung + filter jenis/status untuk tabel
        $merged = $deposits->concat($withdrawals);

        if ($jenis !== 'semua') {
            $merged = $merged->filter(fn($item) => strtolower($item->jenis) === strtolower($jenis));
        }
        if ($status !== 'semua') {
            $merged = $merged->filter(fn($item) => strtolower($item->status) === strtolower($status));
        }

        $merged = $merged->sortByDesc('tanggal')->values();

        // Pagination manual karena data hasil gabungan 2 tabel
        $perPage     = 6;
        $currentPage = Paginator::resolveCurrentPage();
        $transactions = new LengthAwarePaginator(
            $merged->forPage($currentPage, $perPage),
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('admin.transactions.index', compact(
            'transactions', 'isAdmin', 'stats',
            'tanggalMulai', 'tanggalSelesai', 'jenis', 'status', 'cari'
        ));
    }

    public function exchange(Request $req)
    {
        $req->validate([
            'points' => 'required|integer|min:1',
        ]);
        $user = auth()->user();
        $points = $req->input('points');
        if($user->points < $points) return back()->withErrors('Poin tidak cukup');

        $rate = 0.025;
        $amount = $points * ($rate*1000)/1000;

        $user->points -= $points;
        $user->balance += $amount;
        $user->save();

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