<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_messages', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_read')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_messages');
    }
};
