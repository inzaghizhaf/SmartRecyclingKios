<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\TrashEvent;
use App\Models\DailyHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->fresh();

        // Pembaca ESP32 hanya untuk user. Admin tetap masuk ke panelnya sendiri.
        if ($user->role === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $events = TrashEvent::where('user_id',$user->id)
            ->latest()
            ->take(5)
            ->get();

        $history = DailyHistory::where('user_id',$user->id)
            ->whereDate('tanggal',Carbon::today())
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Carbon Footprint
        |--------------------------------------------------------------------------
        */

        $plasticEvents = TrashEvent::where('user_id', $user->id)
            ->whereIn('jenis_sampah', ['plastic', 'plastik', 'Botol Plastik']);

        $canEvents = TrashEvent::where('user_id', $user->id)
            ->whereIn('jenis_sampah', ['can', 'kaleng', 'Kaleng', 'Kaleng Aluminium']);

        $plasticCount = (clone $plasticEvents)->count();
        $canCount = (clone $canEvents)->count();

        /*
            Faktor Emisi

            Faktor tetap per item, bukan per berat atau ukuran kemasan:
            1 botol PET = 0.09 kg CO2e
            1 kaleng aluminium = 0.08 kg CO2e
        */

        $plasticCarbon = (float) (clone $plasticEvents)->sum('carbon_footprint');

        $canCarbon = (float) (clone $canEvents)->sum('carbon_footprint');

        $carbonSaved = $plasticCarbon + $canCarbon;

        $treeEquivalent = $carbonSaved / 21;

        /*
        |--------------------------------------------------------------------------
        | Target Bulanan
        |--------------------------------------------------------------------------
        */

        $targetCarbon = 5;

        $progressCarbon = min(
            100,
            ($carbonSaved / $targetCarbon) * 100
        );

        /*
        |--------------------------------------------------------------------------
        | Badge
        |--------------------------------------------------------------------------
        */

        if($carbonSaved < 1){

            $badge = "🌱 Green Starter";

        }elseif($carbonSaved < 5){

            $badge = "🌿 Eco Hero";

        }else{

            $badge = "🏆 Carbon Saver";

        }

        return view('dashboard',compact(

            'user',
            'events',
            'history',

            'plasticCount',
            'canCount',

            'plasticCarbon',
            'canCarbon',

            'carbonSaved',

            'treeEquivalent',

            'targetCarbon',

            'progressCarbon',

            'badge'

        ));
    }

    public function tarikSaldo(Request $request)
    {
        $request->validate([
            'jumlah'=>'required|numeric|min:1000',
        ]);

        $user = Auth::user();

        if($user->balance < $request->jumlah){

            return back()->with(
                'error',
                'Saldo tidak cukup!'
            );
        }

        $user->balance -= $request->jumlah;

        $user->save();

        return back()->with(
            'success',
            'Saldo berhasil ditarik!'
        );
    }
}
