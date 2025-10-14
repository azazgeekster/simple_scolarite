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
        Schema::create('bac_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->string('type_bac');
            $table->year('annee_bac');
            $table->string('code_serie_bac');
            $table->string('serie_du_bac');
            $table->decimal('moyenne_generale', 5, 2);
            $table->decimal('moyenne_arabe', 5, 2)->nullable();
            $table->decimal('moyenne_francais', 5, 2)->nullable();
            $table->decimal('moyenne_deuxieme_langue', 5, 2)->nullable();
            $table->decimal('moyenne_sciences_physiques', 5, 2)->nullable();
            $table->decimal('moyenne_des_maths', 5, 2)->nullable();
            $table->decimal('moyenne_national', 5, 2)->nullable();
            $table->string('deuxieme_langue');
            $table->string('academie')->nullable();
            $table->string('province_bac')->nullable();
            $table->string('photo_bac')->nullable();
            $table->string('numero_archivage')->nullable();
            $table->timestamps();

            // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bac_info');
    }
};
