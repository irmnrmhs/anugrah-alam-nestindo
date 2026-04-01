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
        Schema::create('detail_steams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orders_id')->constrained('orders');
            $table->decimal('suhu_preheating', 5, 2);
            $table->time('waktu_preheating');
            $table->decimal('suhu_total', 5, 2);
            $table->time('waktu_total');
            $table->integer('jml_tray');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_steams');
    }
};
