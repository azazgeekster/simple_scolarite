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
            $table->foreignId('semester_enrollment_id')
                ->constrained('student_semester_enrollments')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->unsignedMediumInteger('module_id');
            $table->year('registration_year')->nullable();
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes(); // Audit trail


            // Business rules: one active enrollment per module per semester enrollment
            $table->unique(
                ['semester_enrollment_id', 'module_id'],
                'unique_semester_module'
            );
            $table->unsignedTinyInteger('attempt_number')->nullable();

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