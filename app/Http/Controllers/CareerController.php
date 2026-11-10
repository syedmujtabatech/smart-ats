<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobPost;
use App\Services\AiResumeMatcherService;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    protected AiResumeMatcherService $matcherService;

    public function __construct(AiResumeMatcherService $matcherService)
    {
        $this->matcherService = $matcherService;
    }

    public function index(Request $request)
    {
        $query = JobPost::where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('location_type')) {
            $query->where('location_type', $request->location_type);
        }

        $jobs = $query->latest()->paginate(9)->withQueryString();
        $departments = JobPost::where('status', 'published')->distinct()->pluck('department')->filter();

        return view('careers.index', compact('jobs', 'departments'));
    }

    public function show($slug)
    {
        $job = JobPost::where('slug', $slug)->firstOrFail();
        $job->increment('views_count');

        $relatedJobs = JobPost::where('status', 'published')
            ->where('id', '!=', $job->id)
            ->where('department', $job->department)
            ->take(3)
            ->get();

        return view('careers.show', compact('job', 'relatedJobs'));
    }

    public function apply(Request $request, $slug)
    {
        $job = JobPost::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:40',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'current_company' => 'nullable|string|max:150',
            'current_role' => 'nullable|string|max:150',
            'years_of_experience' => 'required|numeric|min:0|max:50',
            'skills_input' => 'nullable|string|max:500',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
            'resume_text' => 'nullable|string',
            'cover_letter' => 'nullable|string',
        ]);

        $candidateSkills = array_filter(array_map('trim', explode(',', $request->input('skills_input', ''))));

        // Find or create Candidate
        $candidate = Candidate::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'linkedin_url' => $validated['linkedin_url'],
                'portfolio_url' => $validated['portfolio_url'],
                'github_url' => $validated['github_url'],
                'current_company' => $validated['current_company'],
                'current_role' => $validated['current_role'],
                'years_of_experience' => $validated['years_of_experience'],
                'skills' => array_values($candidateSkills),
            ]
        );

        // Check if candidate already applied to this specific job
        $existing = Application::where('job_post_id', $job->id)
            ->where('candidate_id', $candidate->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['email' => 'You have already submitted an application for this position!'])->withInput();
        }

        // Handle Resume file upload and text extraction
        $resumePath = null;
        $extractedText = $validated['resume_text'] ?? '';

        if ($request->hasFile('resume_file')) {
            $file = $request->file('resume_file');
            $resumePath = $file->store('resumes', 'public');
            $fileContent = $this->matcherService->extractTextFromFile($file);
            if (!empty($fileContent)) {
                $extractedText = $extractedText ? ($extractedText . "\n\n" . $fileContent) : $fileContent;
            }
        }

        if (empty($extractedText)) {
            $extractedText = "Candidate: {$candidate->name}\nCurrent Role: {$candidate->current_role} at {$candidate->current_company}\nExperience: {$candidate->years_of_experience} years\nSkills: " . implode(', ', $candidateSkills) . "\nNotes: " . ($validated['cover_letter'] ?? '');
        }

        // Create application
        $application = Application::create([
            'job_post_id' => $job->id,
            'candidate_id' => $candidate->id,
            'stage' => 'applied',
            'resume_path' => $resumePath,
            'resume_text' => $extractedText,
            'cover_letter' => $validated['cover_letter'],
            'applied_at' => now(),
        ]);

        // Evaluate immediately with AI
        $eval = $this->matcherService->evaluate($application);

        ActivityLog::create([
            'application_id' => $application->id,
            'user_id' => null,
            'action' => 'applied',
            'description' => "Candidate {$candidate->name} applied. AI Match Score computed: {$eval['match_score']}% ({$eval['recommendation']})",
        ]);

        return redirect()->route('careers.success', ['application' => $application->id]);
    }

    public function success(Application $application)
    {
        $application->load(['candidate', 'jobPost']);
        return view('careers.success', compact('application'));
    }
}
