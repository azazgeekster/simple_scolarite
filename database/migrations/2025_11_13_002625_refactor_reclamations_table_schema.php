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
        Schema::table('reclamations', function (Blueprint $table) {
            // Drop columns we're removing
            $table->dropForeign(['student_id']);
            $table->dropForeign(['module_id']);
            $table->dropIndex(['student_id', 'status']);
            $table->dropUnique('unique_reclamation_per_grade');

            $table->dropColumn(['student_id', 'module_id']);

            // Add session field
            $table->enum('session', ['normal', 'rattrapage'])->nullable()->after('reason')
                ->comment('Exam session for this réclamation');

            // Update unique constraint to use only module_grade_id
            $table->unique('module_grade_id', 'unique_reclamation_per_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamations', function (Blueprint $table) {
            // Drop new unique constraint
            $table->dropUnique('unique_reclamation_per_grade');

            // Drop session column
            $table->dropColumn('session');

            // Restore old columns
            $table->foreignId('student_id')->after('id')->constrained('students')->cascadeOnDelete();
            $table->unsignedMediumInteger('module_id')->after('module_grade_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();

            // Restore indexes
            $table->index(['student_id', 'status']);
            $table->unique(['module_grade_id', 'student_id'], 'unique_reclamation_per_grade');
        });
    }
};
