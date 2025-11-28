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
        Schema::create('grade_import_errors', function (Blueprint $table) {
            $table->id();

            // Import batch information
            $table->string('batch_id', 50)->index()
                ->comment('Unique identifier for this import batch');

            $table->foreignId('admin_id')->comment('Admin who initiated the import')
                ->constrained('admins');

            $table->string('import_type')->default('session_grades')
                ->comment('Type: session_grades, final_grades');

            // File information
            $table->string('filename')->nullable();
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->integer('successful_rows')->default(0);
            $table->integer('error_rows')->default(0);

            // Context
            $table->integer('academic_year')->nullable();
            $table->enum('session', ['normal', 'rattrapage'])->nullable();
            $table->unsignedBigInteger('exam_period_id')->nullable();
            $table->foreign('exam_period_id')->references('id')->on('exam_periods')
                ->nullOnDelete();

            // Error details
            $table->integer('row_number')->nullable()
                ->comment('Row number in the Excel file');

            $table->string('apogee', 20)->nullable();
            $table->string('module_code', 50)->nullable();

            $table->string('error_type', 100)
                ->comment('e.g., student_not_found, invalid_grade, etc.');

            $table->text('error_message')
                ->comment('Detailed error message');

            $table->json('row_data')->nullable()
                ->comment('The actual row data that failed');

            // Status
            $table->enum('status', ['pending', 'failed', 'resolved'])->default('failed');
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('admins');

            $table->timestamps();

            // Indexes
            $table->index(['batch_id', 'status']);
            $table->index(['apogee']);
            $table->index(['module_code']);
            $table->index(['error_type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_import_errors');
    }
};
