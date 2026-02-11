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
            $table->foreignId('feathers_id')->constrained('feathers');
            $table->string('kode')->unique();
            $table->integer('bj_p')->nullable();
            $table->integer('br_p')->nullable();
            $table->integer('bj_pb')->nullable();
            $table->integer('br_pb')->nullable();
            $table->integer('bj_pg')->nullable();
            $table->integer('br_pg')->nullable();
            $table->date('tanggal');
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
