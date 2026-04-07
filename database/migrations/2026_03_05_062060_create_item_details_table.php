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
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grades_id')->constrained('fp_grades');
            $table->foreignId('items_id')->constrained('items');
            $table->string('specification');
            $table->integer('price');
            $table->string('ket')->nullable();
            $table->timestamps();

            $table->unique(['grades_id', 'items_id', 'specification']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_details');
    }
};
