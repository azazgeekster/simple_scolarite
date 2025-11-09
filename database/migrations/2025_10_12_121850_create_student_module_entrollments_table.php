<?php
// Migration: create_student_module_enrollments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_module_enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('program_enrollment_id')
                ->constrained('student_program_enrollments')
                ->cascadeOnDelete();

            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])
                ->nullable()
                ->comment('Semester when module was taken');

            $table->unsignedMediumInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();

            $table->year('registration_year')->nullable();
            $table->unsignedTinyInteger('attempt_number')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Audit trail

            // Business rules: one enrollment per module per program enrollment per semester
            $table->unique(
                ['program_enrollment_id', 'module_id', 'semester'],
                'unique_program_module_semester'
            );

            // Performance indexes
            $table->index(['student_id', 'module_id']);
            $table->index(['student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_module_enrollments');
    }
};