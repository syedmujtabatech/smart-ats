@extends('layouts.admin')

@section('title', 'Kanban Hiring Pipeline')
@section('page_title', 'Visual Candidate Pipeline (Kanban)')

@section('content')
<div class="space-y-6">

    <!-- Top Job Filter Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Pipeline Workflow</h2>
            <p class="text-xs text-slate-500">Track candidates as they advance through interview and decision stages</p>
        </div>

        <form action="{{ route('admin.applications.kanban') }}" method="GET" class="flex items-center gap-2">
            <label class="text-xs font-semibold text-slate-600 whitespace-nowrap">Filter by Requisition:</label>
            <select name="job_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500 min-w-[220px]">
                <option value="">All Requisitions</option>
                @foreach($jobs as $j)
                    <option value="{{ $j->id }}" {{ $selectedJobId == $j->id ? 'selected' : '' }}>{{ $j->title }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Kanban Columns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 items-start overflow-x-auto pb-6">
        
        @foreach($columns as $stageKey => $col)
            <div class="bg-slate-100/80 rounded-2xl p-3 border border-slate-200/70 flex flex-col min-h-[500px]">
                
                <!-- Column Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200 px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-{{ $col['color'] }}-500"></span>
                        <h3 class="font-bold text-xs text-slate-800 tracking-tight">{{ $col['title'] }}</h3>
                    </div>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-white text-slate-600 shadow-xs">
                        {{ $col['items']->count() }}
                    </span>
                </div>

                <!-- Cards list -->
                <div class="space-y-3 flex-1">
                    @forelse($col['items'] as $app)
                        <div class="bg-white rounded-xl p-3.5 border border-slate-200 shadow-xs hover:shadow-md transition-all group">
                            
                            <!-- Header with AI Score Badge -->
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md border {{ $app->ai_score_badge_class }}">
                                    ⚡ {{ $app->ai_match_score ?? '—' }}% Match
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ $app->candidate->years_of_experience }}y exp
                                </span>
                            </div>

                            <!-- Candidate Name & Target Job -->
                            <a href="{{ route('admin.applications.show', $app) }}" class="font-bold text-slate-900 group-hover:text-brand-600 text-sm block">
                                {{ $app->candidate->name }}
                            </a>
                            <div class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                                {{ $app->jobPost->title }}
                            </div>

                            <!-- AI Recommendation snippet -->
                            @if($app->ai_recommendation)
                                <div class="mt-2 text-[10px] text-slate-600 italic line-clamp-2 bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                    "{{ $app->ai_summary }}"
                                </div>
                            @endif

                            <!-- Stage Switcher & Action -->
                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                                <form action="{{ route('admin.applications.stage', $app) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="stage" onchange="this.form.submit()" class="w-full text-[10px] font-semibold py-1 px-1.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 focus:outline-none">
                                        <option value="applied" {{ $stageKey === 'applied' ? 'selected' : '' }}>Applied</option>
                                        <option value="screening" {{ $stageKey === 'screening' ? 'selected' : '' }}>Screening</option>
                                        <option value="interview" {{ $stageKey === 'interview' ? 'selected' : '' }}>Interview</option>
                                        <option value="hr_round" {{ $stageKey === 'hr_round' ? 'selected' : '' }}>HR Round</option>
                                        <option value="offered" {{ $stageKey === 'offered' ? 'selected' : '' }}>Offered</option>
                                        <option value="rejected" {{ $stageKey === 'rejected' ? 'selected' : '' }}>Disqualified</option>
                                    </select>
                                </form>

                                <a href="{{ route('admin.applications.show', $app) }}" title="View AI Report" class="p-1 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>

                        </div>
                    @empty
                        <div class="h-28 flex items-center justify-center border-2 border-dashed border-slate-200 rounded-xl text-[11px] text-slate-400">
                            Empty stage
                        </div>
                    @endforelse
                </div>

            </div>
        @endforeach

    </div>

</div>
@endsection
