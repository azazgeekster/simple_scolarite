<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Modify the result column in module_grades to accept new values
        DB::statement("ALTER TABLE module_grades MODIFY COLUMN result ENUM('V', 'VR', 'NV', 'RATT', 'AC', 'AJ', 'ABI', 'validé', 'validé après rattrapage', 'non validé', 'en attente rattrapage') NULL");
        
        // Step 2: Update module_grades table data
        DB::table('module_grades')
            ->where('result', 'validé')
            ->update(['result' => 'V']);
            
        DB::table('module_grades')
            ->where('result', 'validé après rattrapage')
            ->update(['result' => 'VR']);
            
        DB::table('module_grades')
            ->where('result', 'non validé')
            ->update(['result' => 'NV']);
            
        DB::table('module_grades')
            ->where('result', 'en attente rattrapage')
            ->update(['result' => 'RATT']);

        // Step 3: Modify the final_result column in student_module_enrollments
        DB::statement("ALTER TABLE student_module_enrollments MODIFY COLUMN final_result ENUM('V', 'VR', 'NV', 'RATT', 'AC', 'AJ', 'ABI', 'validé', 'validé après rattrapage', 'non validé', 'en attente rattrapage') NULL");

        // Step 4: Update student_module_enrollments table data
        DB::table('student_module_enrollments')
            ->where('final_result', 'validé')
            ->update(['final_result' => 'V']);
            
        DB::table('student_module_enrollments')
            ->where('final_result', 'validé après rattrapage')
            ->update(['final_result' => 'VR']);
            
        DB::table('student_module_enrollments')
            ->where('final_result', 'non validé')
            ->update(['final_result' => 'NV']);
            
        DB::table('student_module_enrollments')
            ->where('final_result', 'en attente rattrapage')
            ->update(['final_result' => 'RATT']);

        // Step 5: Remove old values from ENUM (final cleanup)
        DB::statement("ALTER TABLE module_grades MODIFY COLUMN result ENUM('V', 'VR', 'NV', 'RATT', 'AC', 'AJ', 'ABI') NULL");
        DB::statement("ALTER TABLE student_module_enrollments MODIFY COLUMN final_result ENUM('V', 'VR', 'NV', 'RATT', 'AC', 'AJ', 'ABI') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Modify columns to accept old values again
        DB::statement("ALTER TABLE module_grades MODIFY COLUMN result ENUM('V', 'VR', 'NV', 'RATT', 'AC', 'AJ', 'ABI', 'validé', 'validé après rattrapage', 'non validé', 'en attente rattrapage') NULL");
        DB::statement("ALTER TABLE student_module_enrollments MODIFY COLUMN final_result ENUM('V', 'VR', 'NV', 'RATT', 'AC', 'AJ', 'ABI', 'validé', 'validé après rattrapage', 'non validé', 'en attente rattrapage') NULL");

        // Step 2: Rollback module_grades table
        DB::table('module_grades')
            ->where('result', 'V')
            ->update(['result' => 'validé']);
            
        DB::table('module_grades')
            ->where('result', 'VR')
            ->update(['result' => 'validé après rattrapage']);
            
        DB::table('module_grades')
            ->where('result', 'NV')
            ->update(['result' => 'non validé']);
            
        DB::table('module_grades')
            ->where('result', 'RATT')
            ->update(['result' => 'en attente rattrapage']);

        // Step 3: Rollback student_module_enrollments table
        DB::table('student_module_enrollments')
            ->where('final_result', 'V')
            ->update(['final_result' => 'validé']);
            
        DB::table('student_module_enrollments')
            ->where('final_result', 'VR')
            ->update(['final_result' => 'validé après rattrapage']);
            
        DB::table('student_module_enrollments')
            ->where('final_result', 'NV')
            ->update(['final_result' => 'non validé']);
            
        DB::table('student_module_enrollments')
            ->where('final_result', 'RATT')
            ->update(['final_result' => 'en attente rattrapage']);

        // Step 4: Restore original ENUM values only
        DB::statement("ALTER TABLE module_grades MODIFY COLUMN result ENUM('validé', 'non validé', 'en attente rattrapage', 'validé après rattrapage') NULL");
        DB::statement("ALTER TABLE student_module_enrollments MODIFY COLUMN final_result ENUM('validé', 'non validé', 'en attente rattrapage', 'validé après rattrapage') NULL");
    }
};
