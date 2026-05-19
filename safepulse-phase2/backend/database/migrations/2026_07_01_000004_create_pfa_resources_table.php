<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pfa_resources', function (Blueprint $t) {
            $t->id();
            $t->enum('action', ['look', 'listen', 'link'])->index();
            $t->string('topic');
            $t->json('content_localized');
            $t->json('referral_targets')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pfa_resources');
    }
};
