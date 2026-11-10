<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\InterviewSchedule;
use App\Models\JobPost;
use App\Services\AiResumeMatcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    protected AiResumeMatcherService $matcherService;

    public function __construct(AiResumeMatcherService $matcherService)
    {
        $this->matcherService = $matcherService;
    }

    public function index(Request $request)
    {
        $query = Application::with(['candidate', 'jobPost']);

        if ($request->filled('job_id')) {
            $query->where('job_post_id', $request->job_id);
        }

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('min_score')) {
            $query->where('ai_match_score', '>=', (int)$request->min_score);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('candidate', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('current_role', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'score_desc');
        if ($sort === 'score_desc') {
            $query->orderByDesc('ai_match_score');
        } elseif ($sort === 'score_asc') {
            $query->orderBy('ai_match_score');
        } elseif ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        }

        $applications = $query->paginate(15)->withQueryString();
        $jobs = JobPost::orderBy('title')->get();

        return view('admin.applications.index', compact('applications', 'jobs'));
    }

    public function kanban(Request $request)
    {
        $jobs = JobPost::orderBy('title')->get();
        $selectedJobId = $request->input('job_id');

        $query = Application::with(['candidate', 'jobPost']);
        if ($selectedJobId) {
            $query->where('job_post_id', $selectedJobId);
        }

        $allApps = $query->orderByDesc('ai_match_score')->get();

        $columns = [
            'applied' => [
                'title' => 'New Applied',
                'color' => 'blue',
                'items' => $allApps->where('stage', 'applied')
            ],
            'screening' => [
                'title' => 'AI Screening',
                'color' => 'purple',
                'items' => $allApps->where('stage', 'screening')
            ],
            'interview' => [
                'title' => 'Technical Interview',
                'color' => 'amber',
                'items' => $allApps->where('stage', 'interview')
            ],
            'hr_round' => [
                'title' => 'HR / Culture Round',
                'color' => 'indigo',
                'items' => $allApps->where('stage', 'hr_round')
            ],
            'offered' => [
                'title' => 'Offer Extended',
                'color' => 'emerald',
                'items' => $allApps->where('stage', 'offered')
            ],
            'rejected' => [
                'title' => 'Disqualified',
                'color' => 'rose',
                'items' => $allApps->where('stage', 'rejected')
            ],
        ];

        return view('admin.applications.kanban', compact('columns', 'jobs', 'selectedJobId'));
    }

    public function show(Application $application)
    {
        $application->load(['candidate', 'jobPost', 'interviewSchedules', 'activityLogs.user']);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStage(Request $request, Application $application)
    {
        $request->validate([
            'stage' => 'required|in:applied,screening,interview,hr_round,offered,rejected,hired',
        ]);

        $oldStage = $application->stage;
        $newStage = $request->stage;

        $application->update(['stage' => $newStage]);

        ActivityLog::create([
            'application_id' => $application->id,
            'user_id' => Auth::id(),
            'action' => 'stage_updated',
            'description' => "Stage changed from {$oldStage} to {$newStage}",
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'stage' => $newStage,
                'badge_class' => $application->stage_badge_class,
            ]);
        }

        return back()->with('success', "Candidate moved to {$newStage} stage.");
    }

    public function reAnalyzeAi(Application $application)
    {
        $result = $this->matcherService->evaluate($application);

        ActivityLog::create([
            'application_id' => $application->id,
            'user_id' => Auth::id(),
            'action' => 'ai_evaluated',
            'description' => "AI Re-screened resume. Score calculated: {$result['match_score']}% ({$result['recommendation']})",
        ]);

        return back()->with('success', "AI evaluation updated! Match score is {$result['match_score']}%.");
    }

    public function scheduleInterview(Request $request, Application $application)
    {
        $validated = $request->validate([
            'interviewer_name' => 'required|string|max:150',
            'interview_type' => 'required|string|max:100',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'meeting_link' => 'nullable|url|max:255',
            'feedback' => 'nullable|string',
        ]);

        $schedule = InterviewSchedule::create([
            'application_id' => $application->id,
            'interviewer_name' => $validated['interviewer_name'],
            'interview_type' => $validated['interview_type'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
            'meeting_link' => $validated['meeting_link'] ?? null,
            'status' => 'scheduled',
            'feedback' => $validated['feedback'] ?? null,
        ]);

        // Auto move to interview stage if in applied/screening
        if (in_array($application->stage, ['applied', 'screening'])) {
            $application->update(['stage' => 'interview']);
        }

        ActivityLog::create([
            'application_id' => $application->id,
            'user_id' => Auth::id(),
            'action' => 'interview_scheduled',
            'description' => "Scheduled {$validated['interview_type']} with {$validated['interviewer_name']}",
        ]);

        return back()->with('success', 'Interview scheduled successfully!');
    }

    public function saveNotes(Request $request, Application $application)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $application->update([
            'notes' => $request->notes,
            'rating' => $request->rating,
        ]);

        ActivityLog::create([
            'application_id' => $application->id,
            'user_id' => Auth::id(),
            'action' => 'note_added',
            'description' => 'Recruiter feedback notes & rating updated.',
        ]);

        return back()->with('success', 'Notes and rating saved.');
    }
}
