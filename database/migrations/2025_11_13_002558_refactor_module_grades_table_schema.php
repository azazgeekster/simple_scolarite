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
        Schema::table('module_grades', function (Blueprint $table) {
            // Drop unique constraint first (it references exam_session which we're dropping)
            $table->dropUnique('unique_enrollment_session');

            // Drop foreign keys and indexes
            $table->dropForeign(['student_id']);
            $table->dropForeign(['module_id']);
            $table->dropForeign(['graded_by']);
            $table->dropIndex(['student_id', 'module_id']);

            // Drop old columns
            $table->dropColumn([
                'student_id',
                'module_id',
                'continuous_assessment',
                'exam_grade',
                'final_grade',
                'exam_session',
                'graded_by',
                'graded_date',
                'is_final',
            ]);

            // Add new columns
            $table->decimal('grade', 5, 2)->nullable()->after('module_enrollment_id')
                ->comment('Final grade out of 20');

            $table->enum('result', [
                'validé',
                'non validé',
                'en attente rattrapage',
                'validé après rattrapage'
            ])->nullable()->after('grade');

            $table->enum('session', ['normal', 'rattrapage'])->nullable()->after('result');

            $table->enum('exam_status', [
                'présent',
                'absent',
                'absent justifié',
                'dispensé'
            ])->nullable()->after('session')
                ->comment('Student exam attendance status');

            // Add new unique constraint on (module_enrollment_id, session)
            $table->unique(['module_enrollment_id', 'session'], 'unique_enrollment_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_grades', function (Blueprint $table) {
            // Drop new columns
            $table->dropUnique('unique_enrollment_session');
            $table->dropColumn(['grade', 'result', 'session', 'exam_status']);

            // Restore old columns
            $table->foreignId('student_id')->after('module_enrollment_id')->constrained('students')->cascadeOnDelete();
            $table->unsignedMediumInteger('module_id')->after('student_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();

            $table->decimal('continuous_assessment', 5, 2)->nullable();
            $table->decimal('exam_grade', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();

            $table->enum('exam_session', ['normal', 'rattrapage', 'validated'])->nullable();
            $table->date('graded_date')->nullable();

            $table->unsignedMediumInteger('graded_by')->nullable();
            $table->foreign('graded_by')->references('id')->on('professors')->onDelete('cascade');

            $table->boolean('is_final')->default(false);

            $table->unique(['module_enrollment_id', 'exam_session'], 'unique_enrollment_session');
            $table->index(['student_id', 'module_id']);
        });
    }
};
