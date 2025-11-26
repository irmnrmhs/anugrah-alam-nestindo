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
        Schema::create('product_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suppliers_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('rms_id')->constrained('raw_materials')->onDelete('cascade');
            $table->foreignId('grades_id')->constrained('grades')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->date('tanggal');
            $table->integer('biji');
            $table->decimal('berat', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_identifiers');
    }
};
