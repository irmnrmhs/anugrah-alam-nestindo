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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orders_id')->constrained('orders');
            $table->integer('item');
            $table->string('spesification');
            $table->integer('packaging');
            $table->decimal('net_wt', 7, 2);
            $table->date('production_date');
            $table->integer('qty_pack');
            $table->decimal('gross_wt', 7, 2);
            $table->integer('cartons');
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
        Schema::dropIfExists('order_details');
    }
};
