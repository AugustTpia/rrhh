<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiring_costs', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7);
            $table->decimal('total_investment', 12, 2)->default(0);
            $table->unsignedInteger('hires_count')->default(0);
            $table->timestamps();

            $table->unique('period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiring_costs');
    }
};
