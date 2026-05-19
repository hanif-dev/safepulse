<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deep_assessments', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignUuid('user_profile_id')->constrained()->cascadeOnDelete();
            $t->string('crime_domain', 32)->index();
            $t->json('answers');
            $t->json('risk_signals');
            $t->enum('mode', ['quick', 'deep'])->default('quick');
            $t->unsignedTinyInteger('completion_pct')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deep_assessments');
    }
};
