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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // BIGINT unsigned, auto-incremented
            $table->string('cne',20)->unique(); // CNE (unique)
            $table->string('prenom'); // First name
            $table->string('nom'); // Last name
            $table->string('prenom_ar')->nullable(); // First name in Arabic
            $table->string('nom_ar')->nullable(); // Last name in Arabic
            $table->string('email')->unique(); // Email (unique)
            $table->string('password'); // Password
            $table->string('apogee',20); // Apogee
            $table->string('cin',20); // CIN
            $table->string('tel', 20)->nullable(); // Phone number
            $table->string('tel_urgence', 20)->nullable(); // Emergency contact phone number
            $table->date('date_naissance')->nullable(); // Date of birth
            $table->string('lieu_naissance')->nullable();
            $table->string('lieu_naissance_ar')->nullable();
            $table->char('nationalite', 5)->nullable(); // Nationality
            $table->enum('situation_familiale', ['Célibataire', 'Marié', 'Divorcé', 'Veuf'])->nullable(); // Marital status
            $table->enum('situation_professionnelle', ['Étudiant', 'Salarié', 'Chômeur', 'Autre'])->nullable(); // Professional status
            $table->text('adresse')->nullable(); // Address
            $table->text('adresse_ar')->nullable(); // Address in Arabic
            $table->enum('sexe', ['M', 'F'])->nullable(); // Sexe (M or F)

            $table->string('pays', 20)->default('Maroc'); // Country (default is Morocco 'MA')
            $table->boolean('boursier')->nullable(); // Scholarship status
            $table->boolean('is_active')->default(false); // Is the student active
            $table->string('activation_token')->nullable(); // Token for activation
            $table->timestamp('last_login')->nullable(); // Last login timestamp
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->string('avatar')->nullable(); // Profile photo
            $table->timestamps(); // created_at and updated_at timestamps
            $table->softDeletes(); // deleted_at for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
