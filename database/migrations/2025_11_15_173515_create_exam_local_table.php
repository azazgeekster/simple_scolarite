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
        Schema::create('exam_local', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('exam_local_id')->constrained('exam_locals')->onDelete('cascade');
            $table->unsignedInteger('allocated_seats')->default(0); // Number of seats used in this local
            $table->unsignedInteger('seat_start')->nullable(); // Starting seat number
            $table->unsignedInteger('seat_end')->nullable(); // Ending seat number
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['exam_id', 'exam_local_id']);

            // Indexes
            $table->index('exam_id');
            $table->index('exam_local_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_local');
    }
};
