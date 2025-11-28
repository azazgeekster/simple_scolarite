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
        Schema::create('justified_absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->unsignedMediumInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();
            $table->integer('academic_year');
            $table->enum('session', ['normal', 'rattrapage']);
            $table->text('justification_reason')->nullable();
            $table->string('justification_document')->nullable(); // File path
            $table->timestamp('justified_at');
            $table->foreignId('justified_by')->constrained('admins')->cascadeOnDelete();
            $table->timestamps();
            
            // Unique constraint: one justification per student per module per session
            $table->unique(['student_id', 'module_id', 'academic_year', 'session'], 'unique_justified_absence');
            
            // Indexes for faster queries
            $table->index(['academic_year', 'session']);
            $table->index('justified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justified_absences');
    }
};
