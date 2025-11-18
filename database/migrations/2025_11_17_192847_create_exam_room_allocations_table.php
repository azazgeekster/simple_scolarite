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
        Schema::create('exam_room_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('local_id')->constrained()->onDelete('cascade');
            $table->integer('allocated_seats'); // Number of students assigned to this room
            $table->timestamps();

            // Ensure unique allocation per exam per local
            $table->unique(['exam_id', 'local_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_room_allocations');
    }
};
