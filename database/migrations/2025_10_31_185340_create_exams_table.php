<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('n_examen');
            
            // What exam?
            $table->unsignedMediumInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();

            $table->smallInteger('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();

            $table->enum('session', ['normal', 'rattrapage']);
            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])->nullable();
            // When & where?
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('local', 50)->comment('Salle/Room');

            $table->timestamps();

            // Indexes
            $table->index(['module_id', 'session', 'academic_year_id']);
            $table->unique(['module_id', 'academic_year_id', 'session']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};