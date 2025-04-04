<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auth_providers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('name')->nullable();
            $table->string('email')->nullable();

            $table->string('provider');
            $table->string('provider_id');
            $table->longText('token');
            $table->longText('refresh_token')->nullable();

            $table->timestamps();
        });
    }
};
