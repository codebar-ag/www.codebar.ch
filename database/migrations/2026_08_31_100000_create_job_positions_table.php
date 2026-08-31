<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->boolean('published')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->string('status');
            $table->string('route_name')->nullable();
            $table->json('title');
            $table->json('teaser')->nullable();
            $table->timestamps();

            $table->index(['published', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_positions');
    }
};
