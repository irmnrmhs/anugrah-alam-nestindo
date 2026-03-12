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
        Schema::create('detail_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documents_id')->constrained('documents');
            $table->foreignId('employees_id')->constrained('employees');
            $table->integer('shift')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_documents');
    }
};
