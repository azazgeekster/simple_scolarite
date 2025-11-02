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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            // Module information
            $table->unsignedMediumInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->cascadeOnDelete();

            // Exam details
            $table->enum('session_type', ['normal', 'rattrapage'])->default('normal');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('local', 50)->nullable();

            // Academic period
            $table->year('academic_year');
            $table->foreign('academic_year')
                ->references('start_year')
                ->on('academic_years')
                ->onDelete('restrict');
            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6']);


            // Additional information
            // $table->text('instructions')->nullable();
            // $table->boolean('is_published')->default(false)->comment('Show to students');
            // $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['module_id', 'session_type', 'academic_year']);
            $table->index(['exam_date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
