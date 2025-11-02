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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();

            // Student and grade information
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('module_grade_id')->constrained('module_grades')->cascadeOnDelete();
            $table->unsignedMediumInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();

            // Reclamation details
            $table->enum('reclamation_type', [
                'grade_calculation_error',
                'missing_grade',
                'transcription_error',
                'exam_paper_review',
                'other'
            ])->default('exam_paper_review');

            $table->text('reason')->comment('Student explanation for grade review');
            $table->text('admin_response')->nullable();

            // Status workflow: PENDING → UNDER_REVIEW → RESOLVED/REJECTED
            $table->enum('status', [
                'PENDING',
                'UNDER_REVIEW',
                'RESOLVED',
                'REJECTED'
            ])->default('PENDING');

            // Grade changes (if any)
            $table->decimal('original_grade', 5, 2)->nullable();
            $table->decimal('revised_grade', 5, 2)->nullable();

            // Processing information
            // $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            // $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['student_id', 'status']);
            $table->index(['module_grade_id']);
            $table->unique(['module_grade_id', 'student_id'], 'unique_reclamation_per_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclammations');
    }
};
