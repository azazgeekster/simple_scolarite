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
        Schema::table('departments', function (Blueprint $table) {
            $table->unsignedMediumInteger('head_id')->nullable();
            $table->foreign('head_id')->references('id')->on('professors')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
        $table->dropForeign(['head_id']); // Drop the foreign key constraint
            $table->dropColumn('head_id');

        });
    }
};
