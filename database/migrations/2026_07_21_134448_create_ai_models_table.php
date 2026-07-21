<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->integer('order');
            $table->string('name');
            $table->string('provider')->nullable();
            $table->string('ram')->nullable();
            $table->string('license')->nullable();
            $table->json('role')->nullable();
            $table->boolean('in_evaluation')->default(false);
            $table->string('link_label')->nullable();
            $table->string('link_url')->nullable();
            $table->date('archived_at')->nullable();
            $table->foreignId('replaced_by_id')->nullable()->constrained('ai_models')->nullOnDelete();
            $table->timestamps();
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
