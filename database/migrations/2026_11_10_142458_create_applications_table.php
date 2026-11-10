<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('stage')->default('applied'); // applied, screening, interview, hr_round, offered, rejected, hired
            $table->string('resume_path')->nullable();
            $table->longText('resume_text')->nullable();
            $table->text('cover_letter')->nullable();
            
            // AI Matching fields
            $table->unsignedTinyInteger('ai_match_score')->nullable(); // 0 to 100
            $table->string('ai_status')->default('pending'); // pending, analyzed, failed
            $table->string('ai_recommendation')->nullable(); // Strong Match, Potential Match, Moderate Match, Low Match
            $table->json('ai_strengths')->nullable();
            $table->json('ai_missing_skills')->nullable();
            $table->text('ai_summary')->nullable();
            $table->string('ai_sentiment')->nullable(); // Positive, Neutral, Negative
            
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('ai_analyzed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
