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
        // This is the key table - student enrolls in a filiere for an academic year
        //Establishes year-by-year program enrollment history

        Schema::create('student_program_enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            $table->unsignedSmallInteger('filiere_id');
            $table->foreign('filiere_id')->references('id')->on('filieres')->cascadeOnDelete();
            $table->year('academic_year'); // ✅ References start_year
            $table->year('registration_year'); // ✅ References start_year

            // Core enrollment data
            $table->unsignedTinyInteger('year_in_program')
                ->comment('1=L1/M1, 2=L2/M2, 3=L3')->nullable();

            $table->enum('enrollment_status', [
                'active',           // Currently studying
                'completed',        // Passed the year
                'failed',           // Failed the year (will repeat)
                'withdrawn',        // Dropped out
                'transferred_out',  // Moved to another filiere
                'suspended'         // Temporarily suspended
            ])->nullable();

            // Important dates
            $table->date('enrollment_date')->nullable();

            // Metadata
            $table->text('notes')->nullable()->comment('Administrative notes');
            $table->timestamps();
            $table->softDeletes(); // For audit trail

            $table->foreign('academic_year')
                ->references('start_year')
                ->on('academic_years')
                ->onDelete('restrict');
            // Business rules
            $table->unique(['student_id', 'academic_year'], 'unique_student_year');
            $table->index(['student_id', 'filiere_id', 'enrollment_status'], 'stud_prog_enroll_idx');

        });

        // ensure one record per (student, year)
        //$table->unique(['student_id', 'academic_year_id']);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_program_enrollments');
    }
};
