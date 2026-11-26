<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infrastructure_connections', function (Blueprint $table) {
            $table->string('flow')->default('constant')->after('to');
        });
    }

    public function down(): void
    {
        Schema::table('infrastructure_connections', function (Blueprint $table) {
            $table->dropColumn('flow');
        });
    }
};
