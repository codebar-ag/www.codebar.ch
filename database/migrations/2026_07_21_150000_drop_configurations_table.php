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
        Schema::dropIfExists('configurations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();

            $table->string('company')->nullable();
            $table->string('company_primary_color')->nullable();

            $table->json('component_intro');

            $table->boolean('section_news')->default(false);
            $table->boolean('section_services')->default(false);
            $table->boolean('section_products')->default(false);
            $table->boolean('section_technologies')->default(false);
            $table->boolean('section_open_source')->default(false);

            $table->string('key');

            $table->json('links')->nullable();

            $table->timestamps();
        });
    }
};
