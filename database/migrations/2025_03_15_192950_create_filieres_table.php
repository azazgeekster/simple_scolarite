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
        Schema::create('filieres', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code', 20)
                  ->comment('e.g., INFO-L, MATH-M, BIO-L');
            $table->string("label_ar",100)->nullable();
            $table->string("label_fr",100);
            $table->string("label_en",100)->nullable();
            // $table->string("code",20);
            $table->enum('level', ['licence', 'master', 'doctorat'])->default('licence');
            $table->unsignedTinyInteger("department_id")->comment("Dept")->nullable();
            $table->unsignedMediumInteger("professor_id")->comment("Coordinateur")->nullable();

            $table->timestamps();

            $table->foreign("department_id")->references("id")->on("departments")->onDelete("set null");
            $table->foreign("professor_id")->references("id")->on("professors")->onDelete("set null");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
