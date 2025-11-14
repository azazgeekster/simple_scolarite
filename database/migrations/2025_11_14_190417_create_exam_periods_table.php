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
        Schema::create('exam_periods', function (Blueprint $table) {
            $table->id();

            // Academic context
            $table->integer('academic_year')->comment('e.g., 2024');
            $table->enum('season', ['autumn', 'spring'])->comment('Automne (S1,S3,S5) ou Printemps (S2,S4,S6)');
            $table->enum('session_type', ['normal', 'rattrapage'])->comment('Session normale ou rattrapage');

            // Period dates
            $table->date('start_date')->comment('When exams start');
            $table->date('end_date')->comment('When exams end');

            // Status and automation
            $table->boolean('is_active')->default(false)->comment('Is this period currently active?');
            $table->boolean('auto_publish_exams')->default(false)->comment('Auto-publish all exams when period starts');

            // Display information
            $table->string('label')->nullable()->comment('e.g., "Session Normale - Automne 2024"');
            $table->text('description')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['academic_year', 'season', 'session_type']);
            $table->index(['start_date', 'end_date']);
            $table->index(['is_active']);

            // Unique constraint: one period per year/season/session
            $table->unique(['academic_year', 'season', 'session_type'], 'unique_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_periods');
    }
};
