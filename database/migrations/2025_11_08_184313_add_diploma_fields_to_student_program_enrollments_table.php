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
        Schema::table('student_program_enrollments', function (Blueprint $table) {
            // Add diploma level (deug, licence, master, doctorat)
            $table->enum('diploma_level', ['DEUG', 'Licence', 'Master', 'Doctorat'])
                ->nullable()
                ->after('year_in_program')
                ->comment('Type de diplôme: deug, licence, master, doctorat');

            // Add diploma year (1, 2, 3, 4, 5...)
            $table->unsignedTinyInteger('diploma_year')
                ->nullable()
                ->after('diploma_level')
                ->comment('Année du diplôme: 1, 2, 3, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_program_enrollments', function (Blueprint $table) {
            $table->dropColumn(['diploma_level', 'diploma_year']);
        });
    }
};
