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
        Schema::table('student_module_enrollments', function (Blueprint $table) {
            // Add final grade and result fields
            $table->decimal('final_grade', 5, 2)->nullable()->after('module_id')
                ->comment('Final grade out of 20 (best of normal/rattrapage sessions)');

            $table->enum('final_result', [
                'validé',
                'non validé',
                'en attente rattrapage',
                'validé après rattrapage'
            ])->nullable()->after('final_grade')
                ->comment('Final validation status for this module');

            // Add index for performance
            $table->index(['final_result']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_module_enrollments', function (Blueprint $table) {
            $table->dropIndex(['final_result']);
            $table->dropColumn(['final_grade', 'final_result']);
        });
    }
};
