<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('trash_events')) {
            return;
        }

        Schema::create('trash_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('jenis_sampah', ['plastic', 'can']);
            $table->integer('poin')->default(0);
            $table->decimal('nilai_rp', 10, 2)->default(0);
            $table->boolean('sensor_proximity')->default(false);
            $table->boolean('sensor_ultrasonic')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash_events');
    }
};
