<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stage_sla_configs', function (Blueprint $table) {
            $table->id();
            $table->string('stage', 100)->unique();
            $table->unsignedTinyInteger('sla_days')->default(3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stage_sla_configs');
    }
};
