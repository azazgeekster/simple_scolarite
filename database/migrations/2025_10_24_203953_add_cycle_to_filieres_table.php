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
        Schema::table('filieres', function (Blueprint $table) {
            $table->enum('cycle', ['licence', 'master', 'doctorat'])
                  ->after('level') // or wherever appropriate
                  ->comment('Academic cycle: licence (L1-L3), master (M1-M2), doctorat (D)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            //
        });
    }
};
