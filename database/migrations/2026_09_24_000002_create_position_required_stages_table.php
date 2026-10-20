<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_required_stages', function (Blueprint $table) {
            $table->id();
            $table->string('position_title', 255);
            $table->string('stage', 100);
            $table->timestamps();

            $table->unique(['position_title', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_required_stages');
    }
};
