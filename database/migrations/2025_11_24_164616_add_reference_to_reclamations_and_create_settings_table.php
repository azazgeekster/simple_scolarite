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
        // Columns already added via partial migration, just add unique index if missing
        if (!Schema::hasIndex('reclamations', 'reclamations_reference_unique')) {
            Schema::table('reclamations', function (Blueprint $table) {
                $table->unique('reference');
            });
        }

        // Create reclamation settings table for activation controls
        Schema::create('reclamation_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('academic_year');
            $table->foreign('academic_year')->references('start_year')->on('academic_years')->cascadeOnDelete();
            $table->enum('session', ['normal', 'rattrapage']);
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->cascadeOnDelete();
            $table->string('semester', 10)->nullable(); // S1, S2, etc.
            $table->foreignId('module_id')->nullable()->constrained('modules')->cascadeOnDelete();
            $table->boolean('is_active')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();

            // Unique constraint for specific combinations
            $table->unique(['academic_year', 'session', 'filiere_id', 'semester', 'module_id'], 'reclamation_settings_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamations', function (Blueprint $table) {
            $table->dropForeign(['corrected_by']);
            $table->dropColumn(['reference', 'corrected_by', 'corrected_at']);
        });

        Schema::dropIfExists('reclamation_settings');
    }
};
