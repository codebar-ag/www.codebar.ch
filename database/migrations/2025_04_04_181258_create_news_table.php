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
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            $table->string('locale');

            $table->string('title');

            $table->string('slug')->unique();

            $table->string('teaser');

            $table->longText('content')->nullable();

            $table->string('image')->nullable();

            $table->dateTime('published_at')->nullable();

            $table->string('author')->nullable();

            $table->json('tags')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
