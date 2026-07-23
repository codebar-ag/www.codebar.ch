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
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('name');
            $table->string('category');
            $table->string('status')->default('active');
            $table->string('cover_disk')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('cover_url')->nullable();
            $table->json('tier_label')->nullable();
            $table->json('excerpt')->nullable();
            $table->string('website')->nullable();
            $table->unsignedSmallInteger('since_year')->nullable();
            $table->unsignedSmallInteger('until_year')->nullable();
            $table->string('page_slug')->nullable();
            $table->boolean('published')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networks');
    }
};
