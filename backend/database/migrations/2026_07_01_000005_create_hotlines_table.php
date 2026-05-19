<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotlines', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name');
            $t->string('country_iso', 2)->index();
            $t->json('contact_channels');
            $t->json('languages_supported');
            $t->enum('availability', ['24_7', 'business_hours', 'custom']);
            $t->string('availability_note')->nullable();
            $t->json('domains_served');
            $t->boolean('verified')->default(false);
            $t->date('verified_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotlines');
    }
};
