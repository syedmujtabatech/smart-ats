@extends('layouts.admin')

@section('title', $job->title . ' - Requisition Pipeline')
@section('page_title', $job->title)

@section('content')
<div class="space-y-6">

    <!-- Job Header Card -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $job->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                    <span class="text-xs text-slate-500 font-medium">{{ $job->department }}</span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="text-xs text-slate-500">{{ $job->employment_type }}</span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="text-xs text-slate-500">{{ $job->location_type }} {{ $job->location_city ? "({$job->location_city})" : '' }}</span>
                </div>

                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $job->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-600 mt-2 font-medium">
                    <span>💰 {{ $job->salary_range_formatted }}</span>
                    <span>•</span>
                    <span>🎯 Experience: {{ $job->experience_level }}</span>
                    <span>•</span>
                    <span>👁️ {{ $job->views_count }} views</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('careers.show', $job->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Public Page ↗
                </a>
                <a href="{{ route('admin.applications.kanban', ['job_id' => $job->id]) }}" class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    Job Kanban
                </a>
                <a href="{{ route('admin.jobs.edit', $job) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-semibold transition-colors">
                    Edit Opening
                </a>
            </div>
        </div>

        <!-- Skills Requirements Pills -->
        @if(!empty($job->required_skills))
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Evaluation Stack:</span>
                @foreach($job->required_skills as $skill)
                    <span class="text-xs bg-brand-50 text-brand-700 font-semibold px-2.5 py-1 rounded-lg border border-brand-200/60">{{ $skill }}</span>
                @endforeach
                @if(!empty($job->nice_to_have_skills))
                    @foreach($job->nice_to_have_skills as $skill)
                        <span class="text-xs bg-slate-100 text-slate-600 font-medium px-2 py-0.5 rounded-lg border border-slate-200">+ {{ $skill }}</span>
                    @endforeach
                @endif
            </div>
        @endif
    </div>

    <!-- Filter & Stage Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        
        <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0">
            <a href="{{ route('admin.jobs.show', [$job]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ !request('stage') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                All ({{ $stageCounts['all'] }})
            </a>
            <a href="{{ route('admin.jobs.show', [$job, 'stage' => 'applied']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ request('stage') == 'applied' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Applied ({{ $stageCounts['applied'] }})
            </a>
            <a href="{{ route('admin.jobs.show', [$job, 'stage' => 'screening']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ request('stage') == 'screening' ? 'bg-purple-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Screening ({{ $stageCounts['screening'] }})
            </a>
            <a href="{{ route('admin.jobs.show', [$job, 'stage' => 'interview']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ request('stage') == 'interview' ? 'bg-amber-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Technical Round ({{ $stageCounts['interview'] }})
            </a>
            <a href="{{ route('admin.jobs.show', [$job, 'stage' => 'offered']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ request('stage') == 'offered' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Offered ({{ $stageCounts['offered'] }})
            </a>
        </div>

        <form action="{{ route('admin.jobs.show', $job) }}" method="GET" class="flex items-center gap-2">
            @if(request('stage'))
                <input type="hidden" name="stage" value="{{ request('stage') }}">
            @endif
            <label class="text-xs text-slate-500 font-semibold">Sort:</label>
            <select name="sort" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="score_desc" {{ request('sort') == 'score_desc' ? 'selected' : '' }}>AI Match Score (High to Low)</option>
                <option value="score_asc" {{ request('sort') == 'score_asc' ? 'selected' : '' }}>AI Match Score (Low to High)</option>
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Recently Applied</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>
        </form>

    </div>

    <!-- Applicants Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Candidate</th>
                        <th class="py-3.5 px-6 text-center">AI Match Score</th>
                        <th class="py-3.5 px-6">Key AI Insights</th>
                        <th class="py-3.5 px-6">Current Stage</th>
                        <th class="py-3.5 px-6">Applied Date</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-sm">
                                    <a href="{{ route('admin.applications.show', $app) }}" class="hover:text-brand-600">
                                        {{ $app->candidate->name }}
                                    </a>
                                </div>
                                <div class="text-slate-500 mt-0.5">
                                    {{ $app->candidate->current_role ?? 'Candidate' }} • {{ $app->candidate->years_of_experience }} yrs exp
                                </div>
                                <div class="text-slate-400 text-[11px]">{{ $app->candidate->email }}</div>
                            </td>

                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="w-12 h-12 rounded-2xl font-extrabold text-sm flex items-center justify-center border {{ $app->ai_score_badge_class }} shadow-xs">
                                        {{ $app->ai_match_score ?? '—' }}%
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-semibold mt-1">
                                        {{ $app->ai_recommendation ?? 'Indexed' }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-4 px-6 max-w-xs">
                                <p class="text-slate-600 line-clamp-2 italic">
                                    "{{ $app->ai_summary ?? 'Candidate evaluation in progress.' }}"
                                </p>
                                @if(!empty($app->ai_strengths))
                                    <div class="flex items-center gap-1 mt-1 text-[11px] text-emerald-700 font-medium">
                                        <span>✓</span> {{ Str::limit($app->ai_strengths[0] ?? '', 45) }}
                                    </div>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                <form action="{{ route('admin.applications.stage', $app) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="stage" onchange="this.form.submit()" class="text-xs font-semibold rounded-lg px-2.5 py-1 border {{ $app->stage_badge_class }} focus:outline-none">
                                        <option value="applied" {{ $app->stage === 'applied' ? 'selected' : '' }}>Applied</option>
                                        <option value="screening" {{ $app->stage === 'screening' ? 'selected' : '' }}>Screening</option>
                                        <option value="interview" {{ $app->stage === 'interview' ? 'selected' : '' }}>Technical Round</option>
                                        <option value="hr_round" {{ $app->stage === 'hr_round' ? 'selected' : '' }}>HR Round</option>
                                        <option value="offered" {{ $app->stage === 'offered' ? 'selected' : '' }}>Offered</option>
                                        <option value="hired" {{ $app->stage === 'hired' ? 'selected' : '' }}>Hired</option>
                                        <option value="rejected" {{ $app->stage === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>

                            <td class="py-4 px-6 text-slate-500">
                                {{ $app->created_at->format('M d, Y') }}
                            </td>

                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.applications.show', $app) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold transition-colors">
                                    View AI Report
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                No candidate applications found in this stage.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $applications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
