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
        Schema::create('academic_years', function (Blueprint $table) {
            // $table->unsignedMediumInteger('id');
            $table->year('start_year')->primary();                  // e.g. "2025"
            $table->year('end_year')->nullable();                    // e.g. "2026"
            $table->boolean('is_current')->default(false); //added by anthropic
            $table->timestamps();
        });
      //  DB::statement('CREATE UNIQUE INDEX unique_current_year ON academic_years (is_current) WHERE is_current = 1');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
