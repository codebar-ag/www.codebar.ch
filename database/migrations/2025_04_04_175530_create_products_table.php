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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(false);
            $table->string('locale');
            $table->integer('order');
            $table->string('name');
            $table->string('headline')->nullable();
            $table->string('teaser');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->string('features_heading')->nullable();
            $table->text('features_intro')->nullable();
            $table->json('features')->nullable();
            $table->string('deployment_heading')->nullable();
            $table->text('deployment_intro')->nullable();
            $table->json('deployment_options')->nullable();
            $table->string('cta_heading')->nullable();
            $table->text('cta_body')->nullable();
            $table->string('image');
            $table->string('url')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->unique(['slug', 'locale']);
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
