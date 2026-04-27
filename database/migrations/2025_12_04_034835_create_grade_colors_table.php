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
        Schema::create('grade_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rms_id')->constrained('raw_materials');
            $table->foreignId('employees_id')->constrained('employees');
            $table->foreignId('feathers_id')->nullable()->constrained('feathers')->nullOnDelete();
            $table->foreignId('colors_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->string('grade');
            // $table->unique(['rms_id', 'grade']);
            $table->integer('biji');
            $table->decimal('berat', 7, 2);
            $table->decimal('other', 7, 2)->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_colors');
    }
};
