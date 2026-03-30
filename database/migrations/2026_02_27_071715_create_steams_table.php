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
        Schema::create('steams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('products_id')->constrained('products');
            // $table->foreignId('officers_id')->constrained('steam_officers');
            // $table->foreignId('nests_id')->constrained('nest_types');
            $table->string('batch')->unique();
            $table->string('petugas');
            $table->boolean('penambahan');
            $table->boolean('sumber_panas');
            $table->date('tgl_pemanasan');
            $table->boolean('lv_air');
            $table->decimal('suhu_awal', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steams');
    }
};
