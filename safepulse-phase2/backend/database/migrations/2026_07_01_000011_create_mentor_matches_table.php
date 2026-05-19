<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_matches', function (Blueprint $t) {
            $t->id();
            $t->uuid('mentee_profile_id');
            $t->uuid('mentor_profile_id');
            $t->string('crime_domain', 32);
            $t->enum('status', ['proposed', 'active', 'closed'])->default('proposed');
            $t->text('moderator_notes')->nullable();
            $t->timestamps();

            $t->index(['mentee_profile_id', 'status']);
            $t->index(['mentor_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_matches');
    }
};
