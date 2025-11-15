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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // Admin ID
            $table->foreign('sender_id')->references('id')->on('admins')->cascadeOnDelete();

            $table->unsignedBigInteger('recipient_id')->nullable(); // Student ID (null for broadcast)
            $table->foreign('recipient_id')->references('id')->on('students')->cascadeOnDelete();

            $table->enum('recipient_type', ['individual', 'filiere', 'year', 'all'])
                ->default('individual')
                ->comment('Who receives this message');

            $table->unsignedSmallInteger('filiere_id')->nullable(); // For filiere-specific messages
            $table->foreign('filiere_id')->references('id')->on('filieres')->cascadeOnDelete();

            $table->integer('year_in_program')->nullable(); // For year-specific messages (1, 2, 3)

            $table->string('subject');
            $table->text('message');

            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('category', ['general', 'exam', 'grade', 'administrative', 'important'])->default('general');

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['recipient_id', 'is_read']);
            $table->index(['recipient_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
