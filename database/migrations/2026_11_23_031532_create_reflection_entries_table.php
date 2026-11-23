<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reflection_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('liked_activities')->nullable();
            $table->json('liked_subjects')->nullable();
            $table->text('interest_notes')->nullable();
            $table->json('compared_options')->nullable();
            $table->text('new_learnings')->nullable();
            $table->text('follow_up_questions')->nullable();
            $table->text('next_action')->nullable();
            $table->boolean('shared_with_bk')->default(false);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reflection_entries');
    }
};