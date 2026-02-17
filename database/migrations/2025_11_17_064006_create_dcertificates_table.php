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
        Schema::create('dcertificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('companies_id')->constrained('companies');
            $table->foreignId('wbhouses_id')->constrained('w_b_houses');
            $table->string('no_skp')->unique();
            $table->date('tgl_skp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcertificates');
    }
};
