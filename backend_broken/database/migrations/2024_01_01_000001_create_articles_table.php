<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('language', 5)->default('en');  // en | id | ja | de
            $table->string('category');                     // scam | radicalization | money_laundering | digital_resilience | youth_peace
            $table->text('summary');
            $table->longText('body_markdown');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['language', 'category']);
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
