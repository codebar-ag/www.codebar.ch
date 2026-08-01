<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('slug');
            $table->json('title');
            $table->json('teaser');
            $table->json('content')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('thumb_image')->nullable();
            $table->json('hero_caption')->nullable();
            $table->json('hero_alt')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('revised_at')->nullable();
            $table->boolean('published')->default(true);
            $table->string('author')->nullable();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('series_id')->nullable()->constrained('news_series')->nullOnDelete();
            $table->unsignedSmallInteger('series_position')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedSmallInteger('reading_minutes')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index(['published', 'published_at']);
        });

        foreach (['de_CH', 'en_CH'] as $locale) {
            $index = 'news_slug_'.strtolower(str_replace('_', '', $locale)).'_unique';
            DB::statement("CREATE UNIQUE INDEX {$index} ON news (( slug->>'{$locale}' ))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
