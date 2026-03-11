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
        Schema::create('analysis_certficates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fproducts_id')->constrained('finished_products');
            $table->foreignId('exports_id')->constrained('exports');
            $table->foreignId('analysis_id')->constrained('analyses');
            $table->date('tanggal');
            $table->boolean('hasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_certficates');
    }
};
