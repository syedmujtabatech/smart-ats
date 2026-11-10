<?php

namespace App\Services;

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiResumeMatcherService
{
    /**
     * Analyze and score an application against the job post.
     *
     * @param Application $application
     * @return array
     */
    public function evaluate(Application $application): array
    {
        $job = $application->jobPost;
        $candidate = $application->candidate;
        $resumeText = $application->resume_text ?? $this->buildFallbackResumeText($candidate, $application);

        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');

        if (!empty($apiKey)) {
            try {
                $geminiResult = $this->evaluateWithGemini($job, $candidate, $resumeText, $apiKey);
                if ($geminiResult) {
                    $this->saveEvaluationToApplication($application, $geminiResult);
                    return $geminiResult;
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini AI evaluation failed, falling back to heuristic matcher: ' . $e->getMessage());
            }
        }

        // Fallback intelligent heuristic & NLP matcher
        $heuristicResult = $this->evaluateWithHeuristic($job, $candidate, $resumeText);
        $this->saveEvaluationToApplication($application, $heuristicResult);

        return $heuristicResult;
    }

    /**
     * Call Gemini API to evaluate resume match.
     */
    protected function evaluateWithGemini(JobPost $job, $candidate, string $resumeText, string $apiKey): ?array
    {
        $prompt = <<<PROMPT
You are a senior tech talent acquisition specialist and ATS AI screener.
Evaluate how well the candidate's resume matches the target job post.

JOB DETAILS:
- Title: {$job->title}
- Department: {$job->department}
- Experience Level: {$job->experience_level}
- Required Skills: {$this->formatSkillsList($job->required_skills)}
- Nice-To-Have Skills: {$this->formatSkillsList($job->nice_to_have_skills)}
- Job Description:
{$job->description}

CANDIDATE DETAILS:
- Name: {$candidate->name}
- Current Role: {$candidate->current_role}
- Years of Experience: {$candidate->years_of_experience} years
- Listed Skills: {$this->formatSkillsList($candidate->skills)}

RESUME CONTENT / APPLICATION NOTE:
{$resumeText}

Respond ONLY with valid JSON in this exact structure without markdown backticks:
{
  "match_score": 85,
  "recommendation": "Strong Match",
  "strengths": ["Skill or experience highlight 1", "Skill 2", "Experience 3"],
  "missing_skills": ["Missing requirement 1", "Gap 2"],
  "summary": "2-3 concise professional sentences summarizing candidate fit and potential risks or strengths.",
  "sentiment": "Positive"
}
PROMPT;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(20)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.2,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($rawText) {
                $cleaned = trim(str_replace(['```json', '```'], '', $rawText));
                $parsed = json_decode($cleaned, true);
                if (is_array($parsed) && isset($parsed['match_score'])) {
                    return [
                        'match_score' => max(0, min(100, (int)$parsed['match_score'])),
                        'recommendation' => $parsed['recommendation'] ?? 'Potential Match',
                        'strengths' => $parsed['strengths'] ?? [],
                        'missing_skills' => $parsed['missing_skills'] ?? [],
                        'summary' => $parsed['summary'] ?? 'Candidate evaluated successfully.',
                        'sentiment' => $parsed['sentiment'] ?? 'Positive',
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Built-in intelligent Heuristic & Rule-based NLP matcher.
     */
    public function evaluateWithHeuristic(JobPost $job, $candidate, string $resumeText): array
    {
        $resumeLower = strtolower($resumeText . ' ' . $candidate->name . ' ' . $candidate->current_role . ' ' . implode(' ', $candidate->skills ?? []));
        $requiredSkills = is_array($job->required_skills) ? $job->required_skills : [];
        $niceSkills = is_array($job->nice_to_have_skills) ? $job->nice_to_have_skills : [];

        $matchedRequired = [];
        $missingRequired = [];

        foreach ($requiredSkills as $skill) {
            $skillLower = strtolower(trim($skill));
            if (empty($skillLower)) continue;

            if (str_contains($resumeLower, $skillLower)) {
                $matchedRequired[] = $skill;
            } else {
                $missingRequired[] = $skill;
            }
        }

        $matchedNice = [];
        foreach ($niceSkills as $skill) {
            $skillLower = strtolower(trim($skill));
            if (!empty($skillLower) && str_contains($resumeLower, $skillLower)) {
                $matchedNice[] = $skill;
            }
        }

        // Skills Score (out of 60 points)
        $reqCount = max(1, count($requiredSkills));
        $reqScore = (count($matchedRequired) / $reqCount) * 50;

        $niceCount = max(1, count($niceSkills));
        $niceScore = (count($matchedNice) / $niceCount) * 10;

        // Experience Score (out of 25 points)
        $expRequired = match (strtolower($job->experience_level)) {
            'entry-level', 'entry', 'junior' => 1,
            'mid-level', 'mid' => 3,
            'senior' => 5,
            'lead', 'principal', 'executive' => 7,
            default => 2
        };

        $candExp = (float)($candidate->years_of_experience ?? 1);
        $expRatio = min(1.3, $candExp / max(1, $expRequired));
        $expScore = min(25, round($expRatio * 25));

        // Keywords & Context (out of 15 points)
        $contextKeywords = ['lead', 'architecture', 'api', 'database', 'testing', 'git', 'ci/cd', 'agile', 'production', 'scalable', 'team', 'design'];
        $contextMatches = 0;
        foreach ($contextKeywords as $kw) {
            if (str_contains($resumeLower, $kw)) {
                $contextMatches++;
            }
        }
        $contextScore = min(15, round(($contextMatches / 6) * 15));

        $totalScore = (int)round($reqScore + $niceScore + $expScore + $contextScore);
        $totalScore = max(15, min(98, $totalScore));

        // Strengths & Gaps
        $strengths = [];
        if (!empty($matchedRequired)) {
            $strengths[] = 'Demonstrated hands-on experience in core stack: ' . implode(', ', array_slice($matchedRequired, 0, 4));
        }
        if ($candExp >= $expRequired) {
            $strengths[] = "Strong track record of {$candExp} years of domain experience, matching the {$job->experience_level} requirement.";
        }
        if (!empty($matchedNice)) {
            $strengths[] = 'Bonus proficiency in secondary tools: ' . implode(', ', array_slice($matchedNice, 0, 3));
        }
        if ($contextMatches >= 4) {
            $strengths[] = 'Demonstrated knowledge of modern production engineering workflows & best practices.';
        }

        // Missing skills
        $missing = [];
        if (!empty($missingRequired)) {
            $missing = array_slice($missingRequired, 0, 5);
        } else {
            $missing[] = 'No critical skill gaps identified for core prerequisites.';
        }

        // Recommendation & Sentiment
        if ($totalScore >= 80) {
            $recommendation = 'Strong Match';
            $sentiment = 'Positive';
            $summary = "Candidate demonstrates a very strong alignment with {$job->title}. Possesses {$candExp} years of relevant experience with proven command in {$this->formatSkillsList(array_slice($matchedRequired, 0, 3))}. Highly recommended for technical screening.";
        } elseif ($totalScore >= 65) {
            $recommendation = 'Potential Match';
            $sentiment = 'Positive';
            $summary = "Solid background matching key requirements for {$job->title}. Shows competent experience ({$candExp} yrs) with potential for immediate onboarding. Recommend verifying proficiency in {$this->formatSkillsList(array_slice($missingRequired, 0, 2))}.";
        } elseif ($totalScore >= 50) {
            $recommendation = 'Moderate Match';
            $sentiment = 'Neutral';
            $summary = "Candidate partially fulfills requirements for {$job->title}. While foundational skills exist, there are observable gaps in key expected competencies ({$this->formatSkillsList(array_slice($missingRequired, 0, 3))}).";
        } else {
            $recommendation = 'Low Match';
            $sentiment = 'Negative';
            $summary = "Profile does not adequately meet the required benchmark for {$job->title}. Substantial skill gaps in core requirements and lower domain seniority than expected.";
        }

        return [
            'match_score' => $totalScore,
            'recommendation' => $recommendation,
            'strengths' => $strengths,
            'missing_skills' => $missing,
            'summary' => $summary,
            'sentiment' => $sentiment,
        ];
    }

    /**
     * Persist evaluation results to the application record.
     */
    protected function saveEvaluationToApplication(Application $application, array $data): void
    {
        $application->update([
            'ai_match_score' => $data['match_score'],
            'ai_recommendation' => $data['recommendation'],
            'ai_strengths' => $data['strengths'],
            'ai_missing_skills' => $data['missing_skills'],
            'ai_summary' => $data['summary'],
            'ai_sentiment' => $data['sentiment'],
            'ai_status' => 'analyzed',
            'ai_analyzed_at' => now(),
        ]);
    }

    protected function formatSkillsList($skills): string
    {
        if (is_array($skills)) {
            return implode(', ', $skills);
        }
        return (string)$skills;
    }

    protected function buildFallbackResumeText($candidate, Application $application): string
    {
        $skillsStr = is_array($candidate->skills) ? implode(', ', $candidate->skills) : ($candidate->skills ?? '');
        return "Candidate: {$candidate->name}\nRole: {$candidate->current_role} at {$candidate->current_company}\nExperience: {$candidate->years_of_experience} years\nSkills: {$skillsStr}\nCover Note: {$application->cover_letter}";
    }

    /**
     * Extract clean text from an uploaded file (supports TXT, text content, etc.)
     */
    public function extractTextFromFile($file): string
    {
        if (!$file) return '';

        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, ['txt', 'md', 'json', 'csv'])) {
            return file_get_contents($file->getRealPath()) ?: '';
        }

        // For pdf/doc or binary files, attempt basic ASCII text stream extraction if possible
        $raw = @file_get_contents($file->getRealPath());
        if ($raw) {
            // Strip binary characters, keep printable ASCII + basic punctuation
            $printable = preg_replace('/[^\x20-\x7E\t\r\n]/', ' ', $raw);
            $words = preg_replace('/\s+/', ' ', $printable);
            if (strlen(trim($words)) > 50) {
                return substr($words, 0, 5000);
            }
        }

        return "Uploaded Resume: " . $file->getClientOriginalName();
    }
}
