<?php
// Migration: create_student_semester_enrollments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_semester_enrollments', function (Blueprint $table) {
            $table->id();

            // References parent program enrollment
            $table->foreignId('program_enrollment_id')
                  ->constrained('student_program_enrollments')
                  ->cascadeOnDelete();

            // $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            // $table->year('academic_year'); // ✅ References start_year
            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])->nullable();

            // Semester-specific status
            $table->enum('enrollment_status', [
                'registered',       // Registered for semester
                'in_progress',      // Semester ongoing
                'completed',        // Semester finished (may have passed/failed modules)
                'withdrawn'         // Withdrew mid-semester
            ])->default('registered');

            $table->timestamps();

            // One semester enrollment per program enrollment per semester
            $table->unique(['program_enrollment_id', 'semester'], 'unique_program_semester');
            $table->index(['semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_semester_enrollments');
    }
};