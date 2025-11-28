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
            // Add corrector_type column to support polymorphic relationship
            $table->string('corrector_type')->nullable()->after('corrected_by')
                ->comment('Type of corrector: Admin or Professor');
        });

        // Set default type to Admin for existing records (after column is created)
        \DB::table('reclamations')
            ->whereNotNull('corrected_by')
            ->update(['corrector_type' => 'App\\Models\\Admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamations', function (Blueprint $table) {
            $table->dropColumn('corrector_type');
        });
    }
};
