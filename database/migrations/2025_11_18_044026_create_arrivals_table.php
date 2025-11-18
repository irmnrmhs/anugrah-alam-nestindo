<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arrivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dcertificates_id')->constrained('dcertificates');
            $table->foreignId('cars_id')->constrained('cars');
            $table->foreignId('drivers_id')->constrained('employees');
            // $table->foreignId('receivers_id')->constrained('employees');
            $table->string('kondisi');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivals');
    }
};
