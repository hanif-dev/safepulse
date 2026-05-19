<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survivor_stories', function (Blueprint $t) {
            $t->id();
            $t->string('anonymous_handle');
            $t->string('crime_domain', 32)->index();
            $t->string('country_iso', 2);
            $t->string('locale', 8);
            $t->text('story_text');
            $t->string('video_url')->nullable();
            $t->date('consent_granted_at');
            $t->date('consent_review_due');
            $t->enum('moderation_status', ['pending', 'approved', 'retired'])->default('pending');
            $t->timestamps();

            $t->index(['moderation_status', 'crime_domain']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survivor_stories');
    }
};
