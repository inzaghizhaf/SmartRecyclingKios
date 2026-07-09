<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Esp32Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua route API untuk komunikasi ESP32 dan Laravel.
| Prefix default-nya: /api
|
*/

// === [1] ESP32 kirim data sensor (utama) ===
Route::post('/esp32/input', [Esp32Controller::class, 'input']);

// === [2] Laravel simpan user_id dari user yang login ===
Route::post('/esp32/set-user', function (Request $request) {
    $user_id = $request->input('user_id');

    if (!$user_id) {
        return response()->json([
            'error' => 'User ID tidak dikirim'
        ], 400);
    }

    // Simpan user_id ke file sementara di storage
    $path = storage_path('app/esp32_user.txt');
    file_put_contents($path, $user_id);

    return response()->json([
        'message' => 'User ID tersimpan di server',
        'user_id' => (int)$user_id
    ], 200);
});

// === [3] ESP32 ambil user_id terakhir dari Laravel ===
Route::get('/esp32/get-user', function () {
    $path = storage_path('app/esp32_user.txt');

    if (!file_exists($path)) {
        return response()->json(['user_id' => null]);
    }

    $user_id = trim(file_get_contents($path));
    return response()->json(['user_id' => (int)$user_id], 200);
});

// === [4] Route default Laravel untuk auth (jangan dihapus) ===
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// === [5] API untuk update data poin & saldo user di dashboard ===
use App\Models\User;

Route::get('/user/{id}/refresh', function ($id) {
    $user = User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User tidak ditemukan'], 404);
    }

    return response()->json([
        'points' => $user->points,
        'balance' => $user->balance,
    ]);
});

// === [6] API untuk detail lengkap user (nama, poin, saldo) ===
Route::get('/esp32/user-detail/{id}', function ($id) {
    $user = User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User tidak ditemukan'], 404);
    }

    return response()->json([
        'user_id' => $user->id,
        'nama_lengkap' => $user->nama_lengkap,
        'points' => $user->points,
        'balance' => $user->balance,
    ]);
});

// === [7] Reset user ID ke 0 dari ESP32 saat tidak aktif ===
Route::post('/esp32/reset-user', function () {
    $path = storage_path('app/esp32_user.txt');
    file_put_contents($path, '0');

    return response()->json(['message' => 'User ID direset ke 0 (logout ESP32)']);
});