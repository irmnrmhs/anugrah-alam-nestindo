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
            $table->foreignId('fproducts_id')->constrained('finished_products');
            $table->foreignId('officers_id')->constrained('steam_officers');
            $table->foreignId('nests_id')->constrained('nest_types');
            $table->boolean('penambahan');
            $table->boolean('sumber_panas');
            $table->string('kode');
            $table->date('tgl_pemanasan');
            $table->boolean('standar');
            $table->decimal('suhu_awal', 5, 2);
            $table->integer('biji');
            $table->decimal('berat', 7, 2);
            $table->decimal('suhu', 5, 2);
            $table->time('waktu');
            $table->decimal('suhu_total', 5, 2);
            $table->time('waktu_total');
            $table->integer('jml_tray');
            $table->string('keterangan')->nullable();
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
