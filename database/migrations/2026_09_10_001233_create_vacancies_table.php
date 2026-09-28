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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->string('title');
            $table->string('branch');
            $table->string('region');
            $table->string('reason'); // nueva_creacion, reemplazo
            $table->string('type');   // publica, confidencial
            $table->text('description')->nullable();
            $table->string('status')->default('activa'); // activa, pausada, cerrada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
