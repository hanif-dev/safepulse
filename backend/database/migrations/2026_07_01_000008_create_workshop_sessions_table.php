<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_sessions', function (Blueprint $t) {
            $t->id();
            $t->string('session_code', 12)->unique();
            $t->string('workshop_name')->default('CyberShield ASEAN 2.0');
            $t->string('facilitator_name');
            $t->string('host_organization')->nullable();
            $t->date('held_on');
            $t->string('location')->nullable();
            $t->unsignedInteger('expected_participants')->default(0);
            $t->json('modules_covered');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_sessions');
    }
};
