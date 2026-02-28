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
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->string('inv')->unique();
            $table->foreignId('customers_id')->constrained('customers');
            $table->foreignId('flights_id')->constrained('flights')->nullable();
            $table->string('contract_no')->unique();
            $table->date('date');
            $table->string('by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
