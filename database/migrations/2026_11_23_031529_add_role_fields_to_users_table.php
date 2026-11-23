<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('identifier')->unique()->after('name');
            $table->string('email')->nullable()->change();
            $table->enum('role', ['admin', 'bk', 'siswa'])->default('siswa')->after('password');
            $table->foreignId('class_group_id')
                ->nullable()
                ->after('role')
                ->constrained('class_groups')
                ->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('class_group_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['class_group_id']);
            $table->dropColumn(['identifier', 'role', 'class_group_id', 'is_active']);
        });
    }
};