<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('category');           // phishing | investment | romance | radicalization | money_laundering | other
            $table->string('country', 100);
            $table->string('age_group', 30)->nullable();
            $table->text('description');
            $table->string('health_impact_level', 10); // low | medium | high
            $table->decimal('financial_loss_estimate', 12, 2)->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('country');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
