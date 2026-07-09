<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboard;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route web aplikasi ini akan dikelola di sini.
|
*/

// Halaman utama
Route::get('/', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return view('auth.login-choice');
})->name('login.choice');

Route::get('/login-tab', function () {
    return view('auth.login');
})->name('login.tab');

Route::get('/login-barcode', function () {
    return view('auth.login-qr');
})->name('login.barcode');


// Dashboard hanya untuk user login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tarik saldo
    Route::post('/tarik-saldo', [DashboardController::class, 'tarikSaldo'])->name('tarik.saldo');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Anda telah logout.');
    })->name('logout');

    // Profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Withdrawal
    Route::post('/tarik-saldo', [BalanceController::class, 'withdraw'])->middleware('auth')->name('tarik.saldo');


    // Daftar harga sampah
    Route::get('/list', function () {
    return view('list');
    })->middleware(['auth'])->name('list');


    // Transaksi (riwayat & penukaran)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/exchange', [TransactionController::class, 'exchange'])->name('transactions.exchange');

    // Contact Us
    Route::get('/contact', function () {
    return view('contact');
    })->middleware(['auth'])->name('contact');

});

// Tambahan: pastikan logout benar-benar hapus session
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login')->with('status', 'Anda telah logout.');
})->name('logout');

// Route auth bawaan Breeze
require __DIR__ . '/auth.php';
