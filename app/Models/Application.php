<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'ai_strengths' => 'array',
            'ai_missing_skills' => 'array',
            'applied_at' => 'datetime',
            'ai_analyzed_at' => 'datetime',
            'ai_match_score' => 'integer',
        ];
    }

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviewSchedules(): HasMany
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    public function getStageBadgeClassAttribute(): string
    {
        return match ($this->stage) {
            'applied' => 'bg-blue-50 text-blue-700 border-blue-200',
            'screening' => 'bg-purple-50 text-purple-700 border-purple-200',
            'interview' => 'bg-amber-50 text-amber-700 border-amber-200',
            'hr_round' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'offered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'hired' => 'bg-green-100 text-green-800 border-green-300',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    public function getAiScoreBadgeClassAttribute(): string
    {
        if ($this->ai_match_score === null) {
            return 'bg-gray-100 text-gray-600 border-gray-200';
        }
        if ($this->ai_match_score >= 80) {
            return 'bg-emerald-100 text-emerald-800 border-emerald-300';
        }
        if ($this->ai_match_score >= 60) {
            return 'bg-amber-100 text-amber-800 border-amber-300';
        }
        return 'bg-rose-100 text-rose-800 border-rose-300';
    }
}
