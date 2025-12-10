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
        Schema::create('picks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('histories_id')->constrained('histories');
            $table->foreignId('employees_id')->constrained('employees');
            $table->date('tgl_mulai');
            $table->integer('biji_masuk');
            $table->decimal('berat_masuk', 7, 2);
            $table->date('tgl_selesai');
            $table->integer('biji_keluar')->default(0);
            $table->decimal('berat_keluar', 7, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picks');
    }
};
