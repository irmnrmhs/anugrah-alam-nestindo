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
            // $table->string('grade')->unique();
            $table->foreignId('shapes_id')->constrained('shapes');
            $table->foreignId('feathers_id')->constrained('feathers');
            $table->foreignId('colors_id')->constrained('colors');
            $table->boolean('status');
            // $table->unique(['shapes_id', 'feathers_id', 'colors_id'], 'grade_unique_combo');
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
