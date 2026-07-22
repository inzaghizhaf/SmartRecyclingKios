<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'superadmin@smartrecycling.local',
        ], [
            'name' => 'Super Admin',
            'nama_lengkap' => 'Super Admin',
            'nomor_telepon' => '-',
            'password' => Hash::make('superadmin123'),
            'konfigurasi_password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'points' => 0,
            'balance' => 0,
        ]);

        User::updateOrCreate([
            'email' => 'admin@smartrecycling.local',
        ], [
            'name' => 'Admin',
            'nama_lengkap' => 'Admin',
            'nomor_telepon' => '-',
            'password' => Hash::make('admin123'),
            'konfigurasi_password' => Hash::make('admin123'),
            'role' => 'admin',
            'points' => 0,
            'balance' => 0,
        ]);
    }
}
