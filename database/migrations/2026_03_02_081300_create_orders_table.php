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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exports_id')->constraine('exports');
            $table->foreignId('batch_id')->constrained('finished_products');
            $table->foreignId('items_id')->constrained('items');
            $table->integer('packaging');
            $table->integer('label');
            $table->decimal('net', 7, 2);
            $table->decimal('gross', 7, 2);
            $table->decimal('amount_fob', 7, 2);
            $table->decimal('amount_cif', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
