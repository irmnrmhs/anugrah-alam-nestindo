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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            // $table->string('kode')->unique();
            // $table->foreignId('arrivals_id')->constrained('arrivals')->unique();
            // $table->foreignId('rw_tests_id')->constrained('rw_tests')->default();
            $table->integer('biji')->default(0);
            $table->decimal('berat', 7, 2)->default(0);
            // $table->decimal('kadar_air', 5, 2); id_uji_bb
            $table->timestamps();
        });
    }
// sy ingin agar ketika pada containers ditambahkan data maka 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
