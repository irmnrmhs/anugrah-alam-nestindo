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
        Schema::create('rm_results', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('types_id')->constrained('test_types');
            $table->foreignId('rms_id')->constrained('raw_materials');
            $table->decimal('kadar_air', 5, 2)->default(0);
            $table->decimal('kadar_nitrit', 4, 1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_results');
    }
};
