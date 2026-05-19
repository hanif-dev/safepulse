<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_aid_contacts', function (Blueprint $t) {
            $t->id();
            $t->string('organization');
            $t->string('parent_network')->nullable();
            $t->string('province')->index();
            $t->json('address');
            $t->json('contact_channels');
            $t->json('case_types_accepted');
            $t->boolean('pro_bono')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_aid_contacts');
    }
};
