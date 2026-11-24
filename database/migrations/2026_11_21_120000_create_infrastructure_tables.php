<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infrastructure_layers', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->string('color', 7);
            $table->json('name');
            $table->timestamps();
        });

        Schema::create('infrastructure_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infrastructure_layer_id')->constrained()->cascadeOnDelete();
            $table->string('key')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->json('name');
            $table->json('text');
            $table->string('provider');
            $table->string('vendor');
            $table->string('vendor_country', 2)->nullable();
            $table->string('place')->default('');
            $table->json('countries');
            $table->string('operation');
            $table->json('links');
            $table->timestamps();
        });

        Schema::create('infrastructure_connections', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->unique(['from', 'to']);
        });

        Schema::create('infrastructure_stacks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->boolean('is_default')->default(false);
            $table->string('name');
            $table->string('domain');
            $table->string('image')->nullable();
            $table->longText('sketch')->nullable();
            $table->json('services');
            $table->json('overrides');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastructure_stacks');
        Schema::dropIfExists('infrastructure_connections');
        Schema::dropIfExists('infrastructure_services');
        Schema::dropIfExists('infrastructure_layers');
    }
};
