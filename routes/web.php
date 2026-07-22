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
use App\Http\Controllers\Admin\AdminPanelController;
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

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Data Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::post('/transactions/exchange', [TransactionController::class, 'exchange'])
        ->name('transactions.exchange');

    Route::get(
    '/carbon-calculator',
    [AdminPanelController::class,'carbonCalculator']
    )->name('carbon.index');

    Route::put(
    '/carbon-calculator/{calculator}',
    [AdminPanelController::class,'updateCarbonCalculator']
    )->name('carbon.update');

    Route::post(
    '/carbon-calculator',
    [AdminPanelController::class,'storeCarbonCalculator']
    )->name('carbon.store');

    Route::delete(
    '/carbon-calculator/{calculator}',
    [AdminPanelController::class,'destroyCarbonCalculator']
    )->name('carbon.destroy');

    Route::get('/', [AdminPanelController::class, 'dashboard'])->name('dashboard');

    // Data User
    Route::get('/users', [AdminPanelController::class, 'users'])->name('users.index');
    Route::post('/users', [AdminPanelController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminPanelController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminPanelController::class, 'destroyUser'])->name('users.destroy');

    // Data Admin
    Route::get('/admins', [AdminPanelController::class, 'admins'])->name('admins.index');
    Route::post('/admins', [AdminPanelController::class, 'storeAdmin'])->name('admins.store');
    Route::put('/admins/{user}', [AdminPanelController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{user}', [AdminPanelController::class, 'destroyAdmin'])->name('admins.destroy');

    // Withdrawal
    Route::get('/withdrawals', [AdminPanelController::class, 'withdrawals'])->name('withdrawals.index');
    Route::patch('/withdrawals/{withdrawal}', [AdminPanelController::class, 'updateWithdrawal'])->name('withdrawals.update');

    // Harga Sampah
    Route::get('/prices', [AdminPanelController::class, 'prices'])->name('prices.index');
    Route::post('/prices', [AdminPanelController::class, 'storePrice'])->name('prices.store');
    Route::put('/prices/{price}', [AdminPanelController::class, 'updatePrice'])->name('prices.update');
    Route::delete('/prices/{price}', [AdminPanelController::class, 'destroyPrice'])->name('prices.destroy');


    // Saldo
    Route::get('/saldo', [AdminPanelController::class, 'saldo'])->name('saldo.index');

    // Dashboard Statistik
    Route::get('/dashboard-statistik', [AdminPanelController::class, 'dashboard'])->name('dashboard.statistik');

});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

    Route::get('/', [AdminPanelController::class, 'dashboard'])->name('dashboard');

    // CRUD Admin
    Route::get('/admins', [AdminPanelController::class, 'admins'])->name('admins.index');
    Route::post('/admins', [AdminPanelController::class, 'storeAdmin'])->name('admins.store');
    Route::put('/admins/{user}', [AdminPanelController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{user}', [AdminPanelController::class, 'destroyAdmin'])->name('admins.destroy');

    // CRUD User
    Route::get('/users', [AdminPanelController::class, 'users'])->name('users.index');

    // Harga
    Route::get('/prices', [AdminPanelController::class, 'prices'])->name('prices.index');

    // Withdrawal
    Route::get('/withdrawals', [AdminPanelController::class, 'withdrawals'])->name('withdrawals.index');

    // Transaksi
    Route::get('/transaksi', [AdminPanelController::class, 'transaksi'])->name('transaksi.index');

    // Saldo
    Route::get('/saldo', [AdminPanelController::class, 'saldo'])->name('saldo.index');

});

// Route auth bawaan Breeze
require __DIR__ . '/auth.php';
