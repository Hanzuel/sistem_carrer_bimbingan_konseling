<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('step_order');
            $table->string('title');
            $table->text('instruction');
            $table->enum('mission_type', [
                'self_reflection',
                'explore_card',
                'find_path',
                'compare_options',
                'next_step',
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};