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
        Schema::table('products', function (Blueprint $table) {
            $table->string('headline')->nullable()->after('name');
            $table->string('deployment_heading')->nullable()->after('content');
            $table->text('deployment_intro')->nullable()->after('deployment_heading');
            $table->json('deployment_options')->nullable()->after('deployment_intro');
            $table->string('cta_heading')->nullable()->after('deployment_options');
            $table->text('cta_body')->nullable()->after('cta_heading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'headline',
                'deployment_heading',
                'deployment_intro',
                'deployment_options',
                'cta_heading',
                'cta_body',
            ]);
        });
    }
};
