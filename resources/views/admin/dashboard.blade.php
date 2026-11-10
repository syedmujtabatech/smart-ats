@extends('layouts.admin')

@section('title', 'Recruitment Dashboard')
@section('page_title', 'Recruitment Intelligence Dashboard')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- Total Active Jobs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Jobs</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-slate-900">{{ $activeJobs }}</div>
                <div class="text-xs text-slate-400 font-medium">of {{ $totalJobs }} total created</div>
            </div>
            <div class="mt-3 text-xs text-emerald-600 font-semibold flex items-center gap-1">
                <span>●</span> Hiring active across departments
            </div>
        </div>

        <!-- Total Screened Applications -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Applicants</span>
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-slate-900">{{ $totalApplications }}</div>
                <div class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-full">100% AI Indexed</div>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-medium">Resumes parsed & scored</div>
        </div>

        <!-- Average AI Match Quality -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Average AI Score</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-slate-900">{{ $avgScore }}<span class="text-lg text-slate-400 font-normal">%</span></div>
                <div class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Benchmark: Good</div>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-medium">Semantic skill correlation</div>
        </div>

        <!-- Interviews & Offers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Interviews & Offers</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-slate-900">{{ $interviewsScheduled + $offersGiven }}</div>
                <div class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-full">{{ $offersGiven }} Offers Extended</div>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-medium">{{ $interviewsScheduled }} in round progression</div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Pipeline Funnel Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Applicant Pipeline Distribution</h2>
                    <p class="text-xs text-slate-500">Live candidate counts across each recruitment lifecycle stage</p>
                </div>
                <a href="{{ route('admin.applications.kanban') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                    Open Kanban Board →
                </a>
            </div>
            <div class="h-64">
                <canvas id="pipelineChart"></canvas>
            </div>
        </div>

        <!-- AI Match Score Distribution Chart -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">AI Match Quality</h2>
                        <p class="text-xs text-slate-500">Breakdown of applicant compatibility</p>
                    </div>
                    <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2 py-1 rounded-lg">Gemini / NLP</span>
                </div>
                <div class="h-52 relative flex items-center justify-center">
                    <canvas id="scoreDistributionChart"></canvas>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600">Strong (85%+)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                    <span class="text-slate-600">Good (70-84%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="text-slate-600">Moderate (50-69%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                    <span class="text-slate-600">Low (&lt;50%)</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Top Ranked Candidates & Activity Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Top Ranked AI Matches -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Top Ranked AI Candidate Matches</h2>
                    <p class="text-xs text-slate-500">Highest matching profiles prioritized by semantic skill correlation</p>
                </div>
                <a href="{{ route('admin.applications.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                    View All Candidates →
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($topMatches as $match)
                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-extrabold text-sm {{ $match->ai_score_badge_class }} border shadow-xs">
                                {{ $match->ai_match_score }}%
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.applications.show', $match) }}" class="font-bold text-slate-900 hover:text-brand-600 text-sm">
                                        {{ $match->candidate->name }}
                                    </a>
                                    <span class="text-[11px] px-2 py-0.5 rounded-full border font-medium {{ $match->stage_badge_class }}">
                                        {{ ucfirst($match->stage) }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $match->candidate->current_role ?? 'Applicant' }} • Applied for <span class="font-semibold text-slate-700">{{ $match->jobPost->title }}</span>
                                </div>
                                <div class="text-xs text-slate-400 mt-1 line-clamp-1 italic">
                                    "{{ $match->ai_summary ?? 'Evaluated and indexed by AI screener.' }}"
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-center">
                            <a href="{{ route('admin.applications.show', $match) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 text-xs font-semibold transition-colors">
                                View AI Report
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">No candidate matches recorded yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Recruitment Activity Log -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-1">Recruitment Timeline</h2>
            <p class="text-xs text-slate-500 mb-4">Latest system events and hiring decisions</p>

            <div class="space-y-4">
                @forelse($activities as $act)
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mt-0.5">
                            ⚡
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-800 font-medium">{{ $act->description }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs">No recent actions recorded.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Pipeline Bar Chart
    const pipelineCtx = document.getElementById('pipelineChart').getContext('2d');
    new Chart(pipelineCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($stageCounts)) !!},
            datasets: [{
                label: 'Candidates',
                data: {!! json_encode(array_values($stageCounts)) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(147, 51, 234, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(244, 63, 94, 0.8)'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 } },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                }
            }
        }
    });

    // Score Doughnut Chart
    const scoreCtx = document.getElementById('scoreDistributionChart').getContext('2d');
    new Chart(scoreCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($scoreDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($scoreDistribution)) !!},
                backgroundColor: [
                    '#10b981', // emerald
                    '#6366f1', // indigo
                    '#f59e0b', // amber
                    '#fb7185'  // rose
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
