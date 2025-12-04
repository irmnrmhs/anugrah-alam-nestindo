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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identifiers_id')->constrained('product_identifiers');
            $table->string('asal')->nullable();
            $table->string('tujuan');
            $table->integer('biji');
            $table->decimal('berat', 7, 3);
            // $table->integer('biji_sisa');
            // $table->decimal('berat_sisa', 7, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
