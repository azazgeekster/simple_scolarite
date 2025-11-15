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
        Schema::create('exam_locals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., "FS2", "AMPHI8"
            $table->string('name'); // e.g., "Salle 2 - Bloc F"
            $table->enum('type', ['salle', 'amphi'])->default('salle');
            $table->enum('bloc', ['F', 'E', 'AMPHI'])->nullable();
            $table->unsignedInteger('capacity'); // Total seats
            $table->unsignedInteger('rows')->nullable(); // Number of rows for seat layout
            $table->unsignedInteger('seats_per_row')->nullable(); // Seats per row
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['type', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_locals');
    }
};
