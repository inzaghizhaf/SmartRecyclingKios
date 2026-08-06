<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trash_events') && !Schema::hasColumn('trash_events', 'carbon_footprint')) {
            Schema::table('trash_events', function (Blueprint $table) {
                $table->decimal('carbon_footprint', 10, 4)->default(0)->after('nilai_rp');
            });
        }

        if (Schema::hasTable('trash_events')) {
            // Faktor tetap per item (bukan berdasarkan berat/ukuran kemasan).
            DB::table('trash_events')->whereIn('jenis_sampah', ['plastic', 'plastik', 'Botol Plastik'])
                ->update(['carbon_footprint' => 0.0900]);
            DB::table('trash_events')->whereIn('jenis_sampah', ['can', 'kaleng', 'Kaleng', 'Kaleng Aluminium'])
                ->update(['carbon_footprint' => 0.0800]);
        }

        if (Schema::hasTable('carbon_calculators')) {
            $now = now();
            foreach ([
                ['waste_type' => 'Botol Plastik', 'co2_factor' => 0.09, 'point_per_kg' => 1, 'tree_factor' => 0.0043],
                ['waste_type' => 'Kaleng Aluminium', 'co2_factor' => 0.08, 'point_per_kg' => 2, 'tree_factor' => 0.0038],
            ] as $factor) {
                $existing = DB::table('carbon_calculators')->where('waste_type', $factor['waste_type'])->first();

                if ($existing) {
                    DB::table('carbon_calculators')->where('id', $existing->id)->update([
                        'co2_factor' => $factor['co2_factor'],
                        'updated_at' => $now,
                    ]);
                } else {
                    DB::table('carbon_calculators')->insert($factor + [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trash_events') && Schema::hasColumn('trash_events', 'carbon_footprint')) {
            Schema::table('trash_events', function (Blueprint $table) {
                $table->dropColumn('carbon_footprint');
            });
        }
    }
};
