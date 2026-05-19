<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('migrant_education_modules', function (Blueprint $t) {
            $t->id();
            $t->unsignedTinyInteger('sequence');
            $t->string('module_code')->unique();
            $t->string('destination_country', 2)->index();
            $t->string('sector', 32)->nullable()->index();
            $t->json('title_localized');
            $t->json('content_localized');
            $t->json('video_urls')->nullable();
            $t->json('pre_post_questions');
            $t->string('source_attribution');
            $t->boolean('published')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migrant_education_modules');
    }
};
