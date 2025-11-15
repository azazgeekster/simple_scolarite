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
        Schema::create('exam_seat_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('exam_local_id')->constrained('exam_locals')->onDelete('cascade');
            $table->unsignedInteger('seat_number'); // Seat number in the local
            $table->string('seat_row')->nullable(); // e.g., "A", "B", "C"
            $table->unsignedInteger('seat_position')->nullable(); // Position in row
            $table->boolean('is_present')->nullable(); // For attendance tracking
            $table->timestamps();

            // Composite unique constraint: one student per exam
            $table->unique(['exam_id', 'student_id'], 'exam_seat_student_unique');

            // Unique constraint: one seat number per local per exam
            $table->unique(['exam_id', 'exam_local_id', 'seat_number'], 'exam_seat_local_number_unique');

            // Indexes for queries
            $table->index('student_id');
            $table->index(['exam_id', 'exam_local_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_seat_assignments');
    }
};
