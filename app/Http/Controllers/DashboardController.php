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

        $plasticCount = TrashEvent::where('user_id',$user->id)
            ->where('jenis_sampah','Botol Plastik')
            ->count();

        $canCount = TrashEvent::where('user_id',$user->id)
            ->where('jenis_sampah','Kaleng')
            ->count();

        /*
            Faktor Emisi

            1 Botol PET
            = 0.05 kg CO2e

            1 Kaleng Aluminium
            = 0.135 kg CO2e
        */

        $plasticCarbon = $plasticCount * 0.05;

        $canCarbon = $canCount * 0.135;

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