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
        Schema::create('grade_feathers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rms_id')->constrained('raw_materials');
            $table->foreignId('employees_id')->constrained('employees');
            $table->string('kode')->unique();
            $table->date('tanggal');
            $table->integer('mk')->nullable();
            $table->integer('ovl')->nullable();
            $table->integer('sdt')->nullable();
            $table->integer('pth')->nullable();
            $table->integer('hcr')->nullable();
            $table->integer('bj_brp')->nullable();
            $table->integer('br_brp')->nullable();
            $table->integer('bj_bs')->nullable();
            $table->integer('br_bs')->nullable();
            $table->integer('bj_bb')->nullable();
            $table->integer('br_bb')->nullable();
            $table->integer('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_feathers');
    }
};
