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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categories_id')->constrained('categories')->onDelete('cascade');
            $table->string('grade')->unique();
            $table->foreignId('shapes_id')->constrained('shapes')->onDelete('cascade');
            $table->foreignId('feathers_id')->constrained('feathers')->onDelete('cascade');
            $table->foreignId('colors_id')->constrained('colors')->onDelete('cascade');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
