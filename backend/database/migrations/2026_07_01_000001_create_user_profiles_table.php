<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('session_token', 64)->unique();
            $t->enum('role', ['victim', 'family', 'professional', 'researcher']);
            $t->string('country_iso', 2);
            $t->string('province_code', 16)->nullable();
            $t->string('locale', 8)->default('id');
            $t->json('consent_flags');
            $t->timestamp('expires_at')->index();
            $t->timestamps();

            $t->index(['role', 'country_iso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
