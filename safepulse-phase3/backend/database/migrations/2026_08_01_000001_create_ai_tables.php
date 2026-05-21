<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 3 — AI service tables.
 * These mirror the SQLAlchemy models in safepulse-ai/db.py.
 * SQLAlchemy creates them on startup, but this migration
 * ensures they are tracked in Laravel's version control.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ai_conversations — anonymous chat sessions
        Schema::create('ai_conversations', function (Blueprint $t) {
            $t->string('id', 36)->primary();
            $t->string('session_token', 64)->unique();
            $t->string('locale', 8)->default('en');
            $t->string('domain_hint', 32)->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->timestamp('expires_at');
            $t->index('expires_at');
        });

        // ai_messages — conversation history
        Schema::create('ai_messages', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('conversation_id', 36)->index();
            $t->string('role', 16);
            $t->longText('content');
            $t->json('sources')->nullable();
            $t->boolean('safety_flagged')->default(false);
            $t->timestamp('created_at')->useCurrent();
        });

        // rag_documents — indexed document metadata
        Schema::create('rag_documents', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('title', 500);
            $t->string('source', 200)->nullable();
            $t->string('organization', 200)->nullable();
            $t->smallInteger('year')->unsigned()->nullable();
            $t->string('url', 1000)->nullable();
            $t->string('file_path', 500)->nullable();
            $t->json('domain_tags')->nullable();
            $t->string('language', 8)->default('en');
            $t->boolean('sunni_scholarly')->default(false);
            $t->integer('chunk_count')->default(0);
            $t->timestamp('indexed_at')->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->index('language');
        });

        // crawl_logs — auto-crawler history
        Schema::create('crawl_logs', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('source', 50)->index();
            $t->string('url', 1000);
            $t->string('status', 20);
            $t->integer('docs_added')->default(0);
            $t->text('error_msg')->nullable();
            $t->timestamp('ran_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawl_logs');
        Schema::dropIfExists('rag_documents');
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('ai_conversations');
    }
};
