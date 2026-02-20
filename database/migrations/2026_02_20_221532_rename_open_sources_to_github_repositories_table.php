<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('open_sources', 'github_repositories');

        DB::table('references')
            ->where('source_type', 'App\\Models\\OpenSource')
            ->update(['source_type' => 'App\\Models\\GithubRepository']);

        DB::table('references')
            ->where('reference_type', 'App\\Models\\OpenSource')
            ->update(['reference_type' => 'App\\Models\\GithubRepository']);
    }

    public function down(): void
    {
        Schema::rename('github_repositories', 'open_sources');

        DB::table('references')
            ->where('source_type', 'App\\Models\\GithubRepository')
            ->update(['source_type' => 'App\\Models\\OpenSource']);

        DB::table('references')
            ->where('reference_type', 'App\\Models\\GithubRepository')
            ->update(['reference_type' => 'App\\Models\\OpenSource']);
    }
};
