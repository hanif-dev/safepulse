<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_documents', function (Blueprint $t) {
            $t->id();
            $t->string('title');                      // "ICCT Report 2026"
            $t->string('source')->nullable();         // "ICCT"
            $t->string('organization')->nullable();   // "Academic" / "Government" / "NGO"
            $t->string('topic');                      // phishing / radicalization / etc.
            $t->string('region')->default('Global');  // Indonesia / ASEAN / Europe
            $t->string('language', 8)->default('en'); // en / id / fr / ar
            $t->year('year')->nullable();
            $t->string('source_url', 1000)->nullable();
            $t->text('description')->nullable();      // short summary
            $t->longText('content')->nullable();      // full markdown / extracted text
            $t->string('file_path', 500)->nullable(); // optional uploaded file
            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->index(['topic', 'is_active']);
            $t->index('region');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_documents');
    }
};
