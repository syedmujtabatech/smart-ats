<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JobPost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'required_skills' => 'array',
            'nice_to_have_skills' => 'array',
            'deadline' => 'date',
            'min_salary' => 'integer',
            'max_salary' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(5);
            }
        });
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function getSalaryRangeFormattedAttribute(): string
    {
        if (!$this->min_salary && !$this->max_salary) {
            return 'Competitive';
        }
        $curr = $this->currency === 'USD' ? '$' : $this->currency . ' ';
        if ($this->min_salary && $this->max_salary) {
            return "{$curr}" . number_format($this->min_salary) . ' - ' . "{$curr}" . number_format($this->max_salary);
        }
        return "Up to {$curr}" . number_format($this->max_salary ?? $this->min_salary);
    }
}
