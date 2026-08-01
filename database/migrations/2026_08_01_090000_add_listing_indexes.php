<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Every listing filters on `published` and sorts on a companion column, and none of
 * those columns carried an index — the cached listings hid the sequential scans.
 *
 * `networks.page_slug` gets a unique constraint rather than a plain index: it is the
 * route key NetworkShowController resolves with ->first(), so a duplicate would make
 * the database pick the partner page arbitrarily.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table): void {
            $table->index(['published', 'published_at']);
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->index(['published', 'order']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->index(['published', 'order']);
        });

        Schema::table('technologies', function (Blueprint $table): void {
            $table->index(['published', 'order']);
        });

        Schema::table('contacts', function (Blueprint $table): void {
            $table->index(['published', 'sort']);
        });

        Schema::table('networks', function (Blueprint $table): void {
            $table->index(['published', 'status', 'sort']);
            $table->unique('page_slug');
        });

        Schema::table('network_users', function (Blueprint $table): void {
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table): void {
            $table->dropIndex(['published', 'published_at']);
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropIndex(['published', 'order']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex(['published', 'order']);
        });

        Schema::table('technologies', function (Blueprint $table): void {
            $table->dropIndex(['published', 'order']);
        });

        Schema::table('contacts', function (Blueprint $table): void {
            $table->dropIndex(['published', 'sort']);
        });

        Schema::table('networks', function (Blueprint $table): void {
            $table->dropIndex(['published', 'status', 'sort']);
            $table->dropUnique(['page_slug']);
        });

        Schema::table('network_users', function (Blueprint $table): void {
            $table->dropUnique(['email']);
        });
    }
};
