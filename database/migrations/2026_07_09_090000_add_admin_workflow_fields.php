<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'status')) {
                $table->string('status')->default('pending')->after('jumlah');
            }

            if (!Schema::hasColumn('withdrawals', 'processed_by')) {
                $table->unsignedBigInteger('processed_by')->nullable()->after('status');
            }

            if (!Schema::hasColumn('withdrawals', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('processed_by');
            }

            if (!Schema::hasColumn('withdrawals', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('processed_at');
            }
        });

        if (!Schema::hasTable('admin_activities')) {
            Schema::create('admin_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->string('activity');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            foreach (['admin_note', 'processed_at', 'processed_by', 'status'] as $column) {
                if (Schema::hasColumn('withdrawals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('admin_activities');
    }
};
