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
        /// a voir MILITAIRE
        Schema::create('student_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('id')->on('students')->cascadeOnDelete();

            // Parents
            $table->string('father_firstname')->nullable();
            $table->string('father_lastname')->nullable();
            $table->string('father_cin')->nullable();
            $table->date('father_birth_date')->nullable();
            $table->date('father_death_date')->nullable();
            $table->string('father_profession')->nullable();

            $table->string('mother_firstname')->nullable();
            $table->string('mother_lastname')->nullable();
            $table->string('mother_cin')->nullable();
            $table->date('mother_birth_date')->nullable();
            $table->date('mother_death_date')->nullable();
            $table->string('mother_profession')->nullable();

            // Spouse
            $table->string('spouse_cin')->nullable();
            $table->date('spouse_death_date')->nullable();

            // Other
            $table->string('handicap_code')->nullable();
            $table->string('handicap_type')->nullable();
            $table->string('handicap_card_number')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_families');
    }
};
