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
        Schema::create('exam_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('n_examen')->nullable(); // Exam number from external system
            $table->string('local')->nullable(); // Can override exam default location
            $table->text('observations')->nullable(); // Special notes for this student
            $table->timestamps();

            // Ensure no duplicate convocations
            $table->unique(['exam_id', 'student_id']);

            // Index for faster queries
            $table->index('student_id');
            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_student');
    }
};
