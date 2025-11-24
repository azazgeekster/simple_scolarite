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
        Schema::table('reclamation_settings', function (Blueprint $table) {
            // Try to drop foreign key constraints if they exist
            try {
                // Get all foreign keys
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = 'reclamation_settings'
                    AND COLUMN_NAME = 'academic_year_id'
                    AND CONSTRAINT_NAME != 'PRIMARY'
                ");

                foreach ($foreignKeys as $fk) {
                    DB::statement("ALTER TABLE reclamation_settings DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                }
            } catch (\Exception $e) {
                // Foreign key might not exist
            }

            // Drop the old column if it exists
            if (Schema::hasColumn('reclamation_settings', 'academic_year_id')) {
                $table->dropColumn('academic_year_id');
            }

            // Add the correct column if it doesn't exist
            if (!Schema::hasColumn('reclamation_settings', 'academic_year')) {
                $table->unsignedSmallInteger('academic_year')->after('id');
                $table->foreign('academic_year')->references('start_year')->on('academic_years')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamation_settings', function (Blueprint $table) {
            $table->dropForeign(['academic_year']);
            $table->dropColumn('academic_year');

            $table->foreignId('academic_year_id')->after('id')->constrained('academic_years')->cascadeOnDelete();
        });
    }
};
