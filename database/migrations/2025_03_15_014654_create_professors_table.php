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
        Schema::create('professors', function (Blueprint $table) {
            $table->mediumIncrements('id'); // BIGINT (PK, AI)
            $table->string('prenom', 50);
            $table->string('nom', 50);
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('specialization', 100)->nullable();
            $table->unsignedTinyInteger('department_id')->nullable();
            $table->timestamps();
            $table->foreign('department_id')->references("id")->on("departments")->onDelete("set null");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};
