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
        Schema::create('grade_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rms_id')->constrained('raw_materials');
            $table->foreignId('employees_id')->constrained('employees');
            $table->foreignId('gfeathers_id')->constrained('grade_feathers');
            $table->foreignId('colors_id')->constrained('colors');
            $table->string('kode')->unique();
            $table->integer('biji');
            $table->integer('berat');
            $table->integer('hcr');
            $table->date('tanggal');
            $table->string('ket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_colors');
    }
};
