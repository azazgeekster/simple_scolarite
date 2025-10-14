<?php
// Migration: create_module_grades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_grades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_enrollment_id')
                  ->constrained('student_module_enrollments')
                  ->cascadeOnDelete();

            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            $table->unsignedMediumInteger('module_id');

            // Grade components (Moroccan system: CC + Exam)
            $table->decimal('continuous_assessment', 5, 2)->nullable()
                  ->comment('Contrôle Continu (CC) - usually 40%');

            $table->decimal('exam_grade', 5, 2)->nullable()
                  ->comment('Final exam grade - usually 60%');

            $table->decimal('final_grade', 5, 2)->nullable()
                  ->comment('Overall grade out of 20');

            // Exam session
            $table->enum('exam_session', [
                'normal',           // Session normale
                'rattrapage',       // Session de rattrapage
                'validated'         // Validated without exam
            ])->nullable();

            // Grade metadata
            $table->date('graded_date')->nullable();
            $table->unsignedMediumInteger('graded_by');
            $table->foreign('graded_by')->nullable()
                  ->references('id')->on('professors')
                  ->onDelete('cascade');

            $table->boolean('is_final')->default(false)
                  ->comment('Is this the final, official grade?');

            $table->text('remarks')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();



            // One grade record per enrollment per session
            $table->unique(
                ['module_enrollment_id', 'exam_session'],
                'unique_enrollment_session'
            );

            $table->index(['student_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_grades');
    }
};