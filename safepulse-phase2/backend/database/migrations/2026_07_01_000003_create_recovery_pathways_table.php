<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recovery_pathways', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('crime_domain', 32)->index();
            $t->json('title');
            $t->json('summary');
            $t->json('milestones');
            $t->json('templates')->nullable();
            $t->json('hotlines')->nullable();
            $t->boolean('published')->default(false)->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_pathways');
    }
};
