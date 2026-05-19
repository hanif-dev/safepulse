<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counter_narrative_links', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('curator');
            $t->string('external_url', 1000);
            $t->string('content_type');
            $t->string('target_audience');
            $t->json('languages');
            $t->boolean('verified')->default(false);
            $t->timestamps();

            $t->index(['target_audience', 'content_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counter_narrative_links');
    }
};
