<?php

declare(strict_types=1);

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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('job_key')->index();
            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('city')->nullable();
            $table->text('interests')->nullable();
            $table->text('focus_fit')->nullable();
            $table->text('built_so_far')->nullable();
            $table->text('about')->nullable();
            $table->string('github')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('project_link')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['job_key', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
