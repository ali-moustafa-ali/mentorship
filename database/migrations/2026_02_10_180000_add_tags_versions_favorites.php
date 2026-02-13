<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('color', 7)->default('#6c63ff');
            $table->timestamps();
        });

        // Pivot table
        Schema::create('tag_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->unique(['tag_id', 'topic_id']);
        });

        // Version history
        Schema::create('topic_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('body');
            $table->integer('version_number');
            $table->string('change_note', 255)->nullable();
            $table->timestamp('created_at');
        });

        // Pinned/favorites column
        Schema::table('topics', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('category');
            $table->integer('view_count')->default(0)->after('is_pinned');
            $table->timestamp('last_reviewed_at')->nullable()->after('view_count');
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'view_count', 'last_reviewed_at']);
        });
        Schema::dropIfExists('topic_versions');
        Schema::dropIfExists('tag_topic');
        Schema::dropIfExists('tags');
    }
};
