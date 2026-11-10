<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJobs = JobPost::count();
        $activeJobs = JobPost::where('status', 'published')->count();
        $totalApplications = Application::count();
        $avgScore = round(Application::whereNotNull('ai_match_score')->avg('ai_match_score') ?? 0);
        $interviewsScheduled = Application::whereIn('stage', ['interview', 'hr_round'])->count();
        $offersGiven = Application::whereIn('stage', ['offered', 'hired'])->count();

        // Stage distribution for Chart
        $stages = [
            'applied' => 'Applied',
            'screening' => 'Screening',
            'interview' => 'Technical Interview',
            'hr_round' => 'HR Round',
            'offered' => 'Offered',
            'rejected' => 'Rejected',
        ];

        $stageCounts = [];
        foreach ($stages as $key => $label) {
            $stageCounts[$label] = Application::where('stage', $key)->count();
        }

        // Score Distribution for Chart
        $scoreDistribution = [
            'Strong (85-100%)' => Application::where('ai_match_score', '>=', 85)->count(),
            'Good (70-84%)' => Application::whereBetween('ai_match_score', [70, 84])->count(),
            'Moderate (50-69%)' => Application::whereBetween('ai_match_score', [50, 69])->count(),
            'Low (<50%)' => Application::where('ai_match_score', '<', 50)->count(),
        ];

        // Recent applications
        $recentApplications = Application::with(['candidate', 'jobPost'])
            ->latest('created_at')
            ->take(6)
            ->get();

        // Top matched candidates
        $topMatches = Application::with(['candidate', 'jobPost'])
            ->whereNotNull('ai_match_score')
            ->orderByDesc('ai_match_score')
            ->take(5)
            ->get();

        // Recent activities
        $activities = ActivityLog::with(['application.candidate', 'application.jobPost'])
            ->latest()
            ->take(7)
            ->get();

        return view('admin.dashboard', compact(
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'avgScore',
            'interviewsScheduled',
            'offersGiven',
            'stageCounts',
            'scoreDistribution',
            'recentApplications',
            'topMatches',
            'activities'
        ));
    }
}
