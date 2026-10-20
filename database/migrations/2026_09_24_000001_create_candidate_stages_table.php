<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->foreignId('vacancy_id')->nullable()->constrained('vacancies')->nullOnDelete();
            $table->string('stage', 100);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['candidate_id', 'stage']);
            $table->index(['vacancy_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_stages');
    }
};
