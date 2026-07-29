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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(false);
            $table->integer('order');
            $table->json('name');
            $table->json('headline')->nullable();
            $table->json('teaser');
            $table->string('slug')->unique();
            $table->json('content')->nullable();
            $table->json('features_heading')->nullable();
            $table->json('features_intro')->nullable();
            $table->json('features')->nullable();
            $table->json('deployment_heading')->nullable();
            $table->json('deployment_intro')->nullable();
            $table->json('deployment_options')->nullable();
            $table->json('cta_heading')->nullable();
            $table->json('cta_body')->nullable();
            $table->string('image');
            $table->string('url')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
