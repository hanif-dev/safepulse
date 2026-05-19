<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $t) {
            $t->id();
            $t->string('namespace', 32);
            $t->string('key');
            $t->string('locale', 8);
            $t->text('value');
            $t->timestamps();

            $t->unique(['namespace', 'key', 'locale']);
            $t->index(['namespace', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
