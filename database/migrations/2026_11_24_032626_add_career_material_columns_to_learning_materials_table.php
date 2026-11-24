<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('learning_materials', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('learning_materials', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (!Schema::hasColumn('learning_materials', 'content_type')) {
                $table->string('content_type', 20)->default('text')->after('summary');
            }
            if (!Schema::hasColumn('learning_materials', 'body')) {
                $table->longText('body')->nullable()->after('content_type');
            }
            if (!Schema::hasColumn('learning_materials', 'file_path')) {
                $table->string('file_path')->nullable()->after('body');
            }
            if (!Schema::hasColumn('learning_materials', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('external_url');
            }
        });

        // Sinkronkan data lama yang sudah ada (seperti 4 modul awal) agar punya slug, content_type, body, dan is_published = true
        $rows = DB::table('learning_materials')->get();
        foreach ($rows as $row) {
            $type = match (strtolower((string) ($row->format_type ?? ''))) {
                'pdf', 'file', 'dokumen' => 'file',
                'link', 'url', 'tautan' => 'link',
                default => !empty($row->external_url) && empty($row->content) ? 'link' : 'text',
            };

            $bodyValue = $row->body;
            if (empty($bodyValue) && !empty($row->content)) {
                $bodyValue = $row->content;
            }
            if (empty($bodyValue) && !empty($row->key_takeaways)) {
                $bodyValue = $row->key_takeaways;
            }

            DB::table('learning_materials')
                ->where('id', $row->id)
                ->update([
                    'slug' => $row->slug ?: Str::slug($row->title) . '-' . $row->id,
                    'category' => $row->category ?: 'Umum',
                    'content_type' => $row->content_type ?: $type,
                    'body' => $bodyValue,
                    'is_published' => is_null($row->is_published) ? true : (bool) $row->is_published,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('learning_materials', function (Blueprint $table) {
            foreach (['user_id', 'slug', 'content_type', 'body', 'file_path', 'is_published'] as $col) {
                if (Schema::hasColumn('learning_materials', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};