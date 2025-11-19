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
        Schema::table('exam_convocations', function (Blueprint $table) {
            $table->foreignId('local_id')->nullable()->after('exam_id')->constrained('locals')->onDelete('set null');
            $table->index('local_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_convocations', function (Blueprint $table) {
            $table->dropForeign(['local_id']);
            $table->dropIndex(['local_id']);
            $table->dropColumn('local_id');
        });
    }
};
