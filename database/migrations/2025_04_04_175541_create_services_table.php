<?php

declare(strict_types=1);

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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(false);
            $table->string('group');
            $table->integer('order');
            $table->json('name');
            $table->json('teaser');
            $table->string('slug')->unique();
            $table->json('content')->nullable();
            $table->string('image');
            $table->string('url')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index(['published', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
