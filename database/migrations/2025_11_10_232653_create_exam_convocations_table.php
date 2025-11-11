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
        Schema::create('exam_convocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_module_enrollment_id')->constrained('student_module_enrollments')->onDelete('cascade');
            $table->string('n_examen')->nullable(); // Exam seat number from external system
            $table->string('location')->nullable(); // Student-specific location (can override exam default)
            $table->text('observations')->nullable(); // Special notes for this student
            $table->timestamps();

            // Ensure no duplicate convocations: student can only be convocated once per exam
            $table->unique(['exam_id', 'student_module_enrollment_id'], 'unique_exam_enrollment');

            // Indexes for faster queries
            $table->index('student_module_enrollment_id', 'idx_enrollment');
            $table->index('exam_id', 'idx_exam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_convocations');
    }
};
