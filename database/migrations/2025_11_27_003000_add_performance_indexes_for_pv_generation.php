<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * These indexes optimize the downloadModulePV query performance
     */
    public function up(): void
    {
        Schema::table('exam_periods', function (Blueprint $table) {
            // For finding exam period by academic_year and session_type
            $table->index(['academic_year', 'session_type'], 'idx_exam_periods_year_session');
        });

        Schema::table('exams', function (Blueprint $table) {
            // For finding exam by period and module
            $table->index(['exam_period_id', 'module_id'], 'idx_exams_period_module');
        });

        Schema::table('module_grades', function (Blueprint $table) {
            // For filtering grades by enrollment and session
            $table->index(['module_enrollment_id', 'session'], 'idx_module_grades_enrollment_session');
        });

        Schema::table('student_module_enrollments', function (Blueprint $table) {
            // For joining with module_grades and filtering by module
            $table->index(['module_id', 'program_enrollment_id'], 'idx_student_module_enrollments_module_program');
        });

        Schema::table('student_program_enrollments', function (Blueprint $table) {
            // For filtering by academic year and joining with students
            $table->index(['academic_year', 'student_id'], 'idx_student_program_enrollments_year_student');
        });

        Schema::table('exam_convocations', function (Blueprint $table) {
            // For joining convocations with enrollments by exam
            $table->index(['exam_id', 'student_module_enrollment_id'], 'idx_exam_convocations_exam_enrollment');
            // For joining with locals to get exam locations
            if (!Schema::hasColumn('exam_convocations', 'local_id')) {
                // Index might already exist from foreign key
                $table->index('local_id', 'idx_exam_convocations_local');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_periods', function (Blueprint $table) {
            $table->dropIndex('idx_exam_periods_year_session');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropIndex('idx_exams_period_module');
        });

        Schema::table('module_grades', function (Blueprint $table) {
            $table->dropIndex('idx_module_grades_enrollment_session');
        });

        Schema::table('student_module_enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_student_module_enrollments_module_program');
        });

        Schema::table('student_program_enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_student_program_enrollments_year_student');
        });

        Schema::table('exam_convocations', function (Blueprint $table) {
            $table->dropIndex('idx_exam_convocations_exam_enrollment');
            $table->dropIndex('idx_exam_convocations_local');
        });
    }
};
