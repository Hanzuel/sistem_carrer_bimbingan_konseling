<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professions', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('title');
            $table->text('main_tasks');
            $table->text('general_skills');
            $table->text('work_environment');
            $table->text('education_paths');
            $table->timestamps();
        });

        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();
            $table->string('format_type')->nullable();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('summary');
            $table->text('key_takeaways')->nullable();
            $table->longText('content')->nullable();
            $table->string('external_url')->nullable();
            $table->timestamps();
        });

        Schema::create('student_quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Misi 1: Kenali Diri
            $table->json('activities')->nullable();
            $table->json('favorite_subjects')->nullable();
            $table->string('study_environment')->nullable();
            $table->text('self_reason')->nullable();

            // Misi 2: Kartu Profesi
            $table->json('read_profession_ids')->nullable();
            $table->json('selected_profession_ids')->nullable();

            // Misi 3: Cari Jalurnya
            $table->json('selected_education_paths')->nullable();

            // Misi 4: Bandingkan Pilihan
            $table->string('career_a_title')->nullable();
            $table->text('career_a_notes')->nullable();
            $table->string('career_b_title')->nullable();
            $table->text('career_b_notes')->nullable();
            $table->text('comparison_conclusion')->nullable();

            // Misi 5: Refleksi & Penutup
            $table->text('learned_summary')->nullable();
            $table->text('bk_discussion_topic')->nullable();
            $table->string('weekly_action')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });

        Schema::create('media_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            for ($i = 1; $i <= 10; $i++) {
                $table->unsignedTinyInteger("q{$i}");
            }
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_evaluations');
        Schema::dropIfExists('student_quests');
        Schema::dropIfExists('learning_materials');
        Schema::dropIfExists('professions');
    }
};