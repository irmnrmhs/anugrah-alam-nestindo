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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exports_id')->constrained('exports');
            $table->foreignId('cars_id')->constrained('cars');
            $table->foreignId('emp_id')->constrained('employees');
            $table->decimal('berat', 7, 2);
            $table->string('kondisi_box');
            $table->string('kemasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
