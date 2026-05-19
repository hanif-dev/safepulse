<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_participants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('workshop_session_id')->constrained()->cascadeOnDelete();
            $t->string('participant_code', 16)->unique();
            $t->json('pre_assessment')->nullable();
            $t->json('post_assessment')->nullable();
            $t->string('certificate_hash', 64)->nullable()->index();
            $t->timestamp('certificate_issued_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_participants');
    }
};
