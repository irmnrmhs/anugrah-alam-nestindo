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
        Schema::create('w_b_houses', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama')->unique();
            $table->string('alamat')->nullable();
            $table->foreignId('areas_id')->constrained('areas');
            $table->decimal('kapasitas', 7, 2);
            $table->string('owner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_b_houses');
    }
};
