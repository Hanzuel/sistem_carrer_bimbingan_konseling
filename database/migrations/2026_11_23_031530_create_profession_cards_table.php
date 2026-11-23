<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profession_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('duties');
            $table->text('work_environment');
            $table->text('general_skills');
            $table->text('education_path');
            $table->text('reference_links')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profession_cards');
    }
};