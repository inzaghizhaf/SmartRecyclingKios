<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nama_lengkap')) {
                $table->string('nama_lengkap')->after('name');
            }

            if (!Schema::hasColumn('users', 'nomor_telepon')) {
                $table->string('nomor_telepon', 20)->after('email');
            }

            if (!Schema::hasColumn('users', 'konfigurasi_password')) {
                $table->string('konfigurasi_password')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'konfigurasi_password')) {
                $table->dropColumn('konfigurasi_password');
            }

            if (Schema::hasColumn('users', 'nomor_telepon')) {
                $table->dropColumn('nomor_telepon');
            }

            if (Schema::hasColumn('users', 'nama_lengkap')) {
                $table->dropColumn('nama_lengkap');
            }
        });
    }
};
