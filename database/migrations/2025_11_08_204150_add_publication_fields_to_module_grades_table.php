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
        Schema::table('module_grades', function (Blueprint $table) {
            // Add grade publication tracking
            $table->boolean('is_published')->default(false)->after('is_final')
                ->comment('Whether grade is visible to student');

            $table->timestamp('published_at')->nullable()->after('is_published')
                ->comment('When grade was published to student');

            $table->unsignedBigInteger('published_by')->nullable()->after('published_at')
                ->comment('Admin who published the grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_grades', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'published_at', 'published_by']);
        });
    }
};
