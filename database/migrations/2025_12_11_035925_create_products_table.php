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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('histories_id')->constrained('histories');
            $table->foreignId('employees_id')->constrained('employees');
            $table->foreignId('grades_id')->constrained('fp_grades');
            $table->string('kode')->unique();
            $table->date('tgl_mulai');
            $table->integer('biji');
            $table->decimal('berat', 7, 2);
            $table->date('tgl_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
