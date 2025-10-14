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
        Schema::create('documents', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
                 // e.g. "attestation", "releve_de_notes"

            $table->string('label_ar', 100)->nullable();         // e.g. "Attestation de scolarité"
            $table->string('label_fr', 100);         // e.g. "Attestation de scolarité"
            $table->string('label_en', 100)->nullable();
            $table->string('template_path')->nullable();
                // Blade/PDF template file, e.g. "pdfs/attestation.blade.php"
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
