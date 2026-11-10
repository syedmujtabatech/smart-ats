<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department');
            $table->string('location_type')->default('Remote');
            $table->string('location_city')->nullable();
            $table->string('employment_type')->default('Full-time');
            $table->string('experience_level')->default('Mid-Level');
            $table->unsignedInteger('min_salary')->nullable();
            $table->unsignedInteger('max_salary')->nullable();
            $table->string('currency')->default('USD');
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->json('required_skills')->nullable();
            $table->json('nice_to_have_skills')->nullable();
            $table->string('status')->default('published');
            $table->date('deadline')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
