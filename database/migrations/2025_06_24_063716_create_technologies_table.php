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
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(false);
            $table->string('locale');
            $table->string('group');
            $table->integer('order');
            $table->string('title');
            $table->string('slug');
            $table->string('teaser');
            $table->longText('content')->nullable();
            $table->string('image');
            $table->json('tags')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
            $table->unique(['slug', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technologies');
    }
};
