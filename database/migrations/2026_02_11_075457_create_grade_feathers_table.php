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
            $table->date('tgl');
            $table->integer('mk')->nullable()->default(0)->default(0);
            $table->integer('ovl')->nullable()->default(0);
            $table->integer('sdt')->nullable()->default(0);
            $table->integer('pth')->nullable()->default(0);
            $table->integer('hcr')->nullable()->default(0);
            $table->integer('bj_brp')->nullable()->default(0);
            $table->integer('br_brp')->nullable()->default(0);
            $table->integer('bj_bs')->nullable()->default(0);
            $table->integer('br_bs')->nullable()->default(0);
            $table->integer('bj_bb')->nullable()->default(0);
            $table->integer('br_bb')->nullable()->default(0);
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
