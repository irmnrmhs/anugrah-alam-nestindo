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
        Schema::create('detail_skps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dcertificates_id')->constrained('dcertificates');
            $table->date('tgl_panen');
            $table->decimal('berat_panen', 7, 2);
            $table->date('tgl_kirim');
            $table->decimal('berat_kirim', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_skps');
    }
};
