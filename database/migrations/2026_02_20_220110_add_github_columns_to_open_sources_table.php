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
        Schema::table('open_sources', function (Blueprint $table) {
            $table->string('github_name')->nullable()->after('version');
            $table->integer('stars')->default(0)->after('github_name');
            $table->integer('forks')->default(0)->after('stars');
            $table->string('primary_language')->nullable()->after('forks');
        });
    }

    public function down(): void
    {
        Schema::table('open_sources', function (Blueprint $table) {
            $table->dropColumn(['github_name', 'stars', 'forks', 'primary_language']);
        });
    }
};
