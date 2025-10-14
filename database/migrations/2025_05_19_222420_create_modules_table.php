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
        Schema::create('modules', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('code',10)->unique()->comment('e.g., BM324, MATH101, INFO205');
            $table->string('label',100);
            $table->string('label_ar',100)->nullable();
            $table->unsignedSmallInteger("filiere_id")->nullable();
            // $table->string('filiere_code', 20);
            $table->foreign('filiere_id')
            ->references('id')
            ->on('filieres')
            ->cascadeOnDelete();
            $table->unsignedTinyInteger('year_in_program')->comment('Which year: 1, 2, 3...')->nullable();
            $table->enum('semester', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'])->nullable();
            $table->unsignedTinyInteger('cc_percentage')->default(0)
            ->comment('Continuous assessment weight %');
      $table->unsignedTinyInteger('exam_percentage')->default(100)
            ->comment('Final exam weight %');

            // $table->unsignedTinyInteger('semester')->nullable();
            $table->unsignedMediumInteger("professor_id")->nullable()->comment('respo');
            $table->unsignedMediumInteger('prerequisite_id')->nullable(); // Self-referencing FK

            $table->timestamps();

            // $table->foreign("semester_id")->references("semester_number")->on("semesters")->onDelete("CASCADE");
            $table->foreign("prerequisite_id")->references("id")->on("modules")->onDelete("set null");
            // $table->foreign("filiere_id")->references("id")->on("filieres")->onDelete("set null");
            $table->foreign("professor_id")->references("id")->on("professors")->onDelete("set null");

            // $table->check('cc_percentage + exam_percentage = 100');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
