<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->foreignId('vacancy_id')->nullable()->constrained('vacancies')->nullOnDelete();
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->timestamps();

            $table->index('hire_date');
            $table->index('termination_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hires');
    }
};
