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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained('vacancies')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('source')->default('Directo'); // Indeed, OCC, LinkedIn, Directo, etc.
            $table->decimal('salary_expectation', 10, 2)->nullable();
            $table->string('stage')->default('contacto'); // contacto, psicotecnicas, entrevistas, manejo, socioeconomico, contratado, descartado
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
