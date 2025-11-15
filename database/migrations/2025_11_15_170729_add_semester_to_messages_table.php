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
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])->nullable()->after('year_in_program');

            // Update recipient_type to include new options
            $table->dropColumn('recipient_type');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->enum('recipient_type', [
                'individual',
                'filiere',
                'year',
                'semester',
                'filiere_year',
                'filiere_semester',
                'all'
            ])->default('individual')->after('recipient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->dropColumn('recipient_type');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->enum('recipient_type', ['individual', 'filiere', 'year', 'all'])
                ->default('individual')
                ->after('recipient_id');
        });
    }
};
