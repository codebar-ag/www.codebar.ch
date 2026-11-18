<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odoo_price_snapshots', function (Blueprint $table) {
            $table->id();
            $table->json('prices');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odoo_price_snapshots');
    }
};
