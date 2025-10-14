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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // Auto-generated: REQ-2025-00001

            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            $table->unsignedSmallInteger('document_id');
            $table->year('academic_year');
            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])->nullable();

            // Status flow: PENDING → PROCESSING → READY_FOR_PICKUP → COMPLETED
            $table->enum('status', [
                'PENDING',
                'READY',
                'PICKED',
                'COMPLETED'
            ])->default('PENDING');
            $table->text('reason')->nullable(); // Why they need it

            $table->enum('retrait_type', ['temporaire', 'permanent'])->nullable();

            // $table->text('rejection_reason')->nullable();
            $table->foreign('document_id')->references("id")->on("documents")->onDelete("CASCADE");
            // $table->foreign('semester_id')->references("id")->on("semesters")->onDelete("CASCADE");
            // $table->unsignedTinyInteger('semester');
            // $table->foreign('academic_year_id')->references("id")->on("academic_years")->onDelete("CASCADE");
            $table->foreign('academic_year')
                ->references('start_year')
                ->on('academic_years')
                ->onDelete('restrict');
            $table->foreignId('processed_by')->nullable()->constrained('admins');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('ready_at')->nullable();

            $table->timestamp('must_return_by')->nullable();     // deadline to return
            $table->timestamp('returned_at')->nullable();        // when it was returned
            $table->timestamp('extension_requested_at')->nullable(); // if extra time requested
            $table->unsignedTinyInteger('extension_days')->nullable();   // how many extra days requested

            $table->timestamp('collected_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
