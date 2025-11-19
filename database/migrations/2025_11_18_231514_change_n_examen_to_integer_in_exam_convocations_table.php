<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear existing string values first
        DB::table('exam_convocations')->update(['n_examen' => null]);

        Schema::table('exam_convocations', function (Blueprint $table) {
            $table->unsignedInteger('n_examen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_convocations', function (Blueprint $table) {
            $table->string('n_examen')->nullable()->change();
        });
    }
};
