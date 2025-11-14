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
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('exam_period_id')->nullable()->after('id')
                ->constrained('exam_periods')
                ->nullOnDelete()
                ->comment('Links exam to a managed exam period');

            $table->index(['exam_period_id', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['exam_period_id']);
            $table->dropIndex(['exam_period_id', 'is_published']);
            $table->dropColumn('exam_period_id');
        });
    }
};
