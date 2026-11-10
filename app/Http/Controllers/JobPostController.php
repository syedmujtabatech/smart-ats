<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::withCount('applications');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();
        $departments = JobPost::distinct()->pluck('department')->filter();

        return view('admin.jobs.index', compact('jobs', 'departments'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'location_type' => 'required|in:Remote,Onsite,Hybrid',
            'location_city' => 'nullable|string|max:150',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Internship',
            'experience_level' => 'required|in:Entry-Level,Mid-Level,Senior,Lead',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'currency' => 'nullable|string|max:10',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'required_skills_input' => 'nullable|string',
            'nice_to_have_skills_input' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'required|in:published,draft,closed',
        ]);

        $requiredSkills = array_filter(array_map('trim', explode(',', $request->input('required_skills_input', ''))));
        $niceSkills = array_filter(array_map('trim', explode(',', $request->input('nice_to_have_skills_input', ''))));

        $job = JobPost::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::lower(Str::random(5)),
            'department' => $validated['department'],
            'location_type' => $validated['location_type'],
            'location_city' => $validated['location_city'] ?? null,
            'employment_type' => $validated['employment_type'],
            'experience_level' => $validated['experience_level'],
            'min_salary' => $validated['min_salary'] ?? null,
            'max_salary' => $validated['max_salary'] ?? null,
            'currency' => $validated['currency'] ?? 'USD',
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'required_skills' => array_values($requiredSkills),
            'nice_to_have_skills' => array_values($niceSkills),
            'deadline' => $validated['deadline'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.jobs.show', $job)->with('success', 'Job posting created successfully!');
    }

    public function show(JobPost $job, Request $request)
    {
        $job->loadCount('applications');

        $query = $job->applications()->with('candidate');

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'score_desc') {
                $query->orderByDesc('ai_match_score');
            } elseif ($request->sort === 'score_asc') {
                $query->orderBy('ai_match_score');
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->orderByDesc('ai_match_score');
        }

        $applications = $query->paginate(12)->withQueryString();

        $stageCounts = [
            'all' => $job->applications()->count(),
            'applied' => $job->applications()->where('stage', 'applied')->count(),
            'screening' => $job->applications()->where('stage', 'screening')->count(),
            'interview' => $job->applications()->where('stage', 'interview')->count(),
            'hr_round' => $job->applications()->where('stage', 'hr_round')->count(),
            'offered' => $job->applications()->where('stage', 'offered')->count(),
            'rejected' => $job->applications()->where('stage', 'rejected')->count(),
        ];

        return view('admin.jobs.show', compact('job', 'applications', 'stageCounts'));
    }

    public function edit(JobPost $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPost $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'location_type' => 'required|in:Remote,Onsite,Hybrid',
            'location_city' => 'nullable|string|max:150',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Internship',
            'experience_level' => 'required|in:Entry-Level,Mid-Level,Senior,Lead',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'currency' => 'nullable|string|max:10',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'required_skills_input' => 'nullable|string',
            'nice_to_have_skills_input' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'required|in:published,draft,closed',
        ]);

        $requiredSkills = array_filter(array_map('trim', explode(',', $request->input('required_skills_input', ''))));
        $niceSkills = array_filter(array_map('trim', explode(',', $request->input('nice_to_have_skills_input', ''))));

        $job->update([
            'title' => $validated['title'],
            'department' => $validated['department'],
            'location_type' => $validated['location_type'],
            'location_city' => $validated['location_city'] ?? null,
            'employment_type' => $validated['employment_type'],
            'experience_level' => $validated['experience_level'],
            'min_salary' => $validated['min_salary'] ?? null,
            'max_salary' => $validated['max_salary'] ?? null,
            'currency' => $validated['currency'] ?? 'USD',
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'required_skills' => array_values($requiredSkills),
            'nice_to_have_skills' => array_values($niceSkills),
            'deadline' => $validated['deadline'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.jobs.show', $job)->with('success', 'Job posting updated successfully!');
    }

    public function destroy(JobPost $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job posting deleted successfully.');
    }
}
