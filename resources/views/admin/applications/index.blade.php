@extends('layouts.admin')

@section('title', 'Candidates & AI Screener')
@section('page_title', 'Candidate Talent Pool')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.applications.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Search Candidates</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, role..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Job Opening -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Job Opening</label>
                <select name="job_id" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Job Openings</option>
                    @foreach($jobs as $j)
                        <option value="{{ $j->id }}" {{ request('job_id') == $j->id ? 'selected' : '' }}>{{ $j->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pipeline Stage -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Stage</label>
                <select name="stage" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Stages</option>
                    <option value="applied" {{ request('stage') == 'applied' ? 'selected' : '' }}>Applied</option>
                    <option value="screening" {{ request('stage') == 'screening' ? 'selected' : '' }}>Screening</option>
                    <option value="interview" {{ request('stage') == 'interview' ? 'selected' : '' }}>Technical Interview</option>
                    <option value="hr_round" {{ request('stage') == 'hr_round' ? 'selected' : '' }}>HR Round</option>
                    <option value="offered" {{ request('stage') == 'offered' ? 'selected' : '' }}>Offered</option>
                    <option value="rejected" {{ request('stage') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Min AI Score & Submit -->
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Min AI Score %</label>
                    <input type="number" name="min_score" min="0" max="100" value="{{ request('min_score') }}" placeholder="e.g. 70"
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs rounded-xl transition-colors mt-auto">
                    Filter
                </button>
            </div>

        </form>
    </div>

    <!-- Candidates Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Candidate</th>
                        <th class="py-3.5 px-6">Target Requisition</th>
                        <th class="py-3.5 px-6 text-center">AI Match Score</th>
                        <th class="py-3.5 px-6">AI Recommendation</th>
                        <th class="py-3.5 px-6">Current Stage</th>
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

                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $app->jobPost->title }}</div>
                                <div class="text-slate-400 text-[11px]">{{ $app->jobPost->department }}</div>
                            </td>

                            <td class="py-4 px-6 text-center">
                                <span class="w-11 h-11 mx-auto rounded-2xl font-extrabold text-sm flex items-center justify-center border {{ $app->ai_score_badge_class }} shadow-xs">
                                    {{ $app->ai_match_score ?? '—' }}%
                                </span>
                            </td>

                            <td class="py-4 px-6">
                                <span class="text-xs font-bold text-slate-800 block">
                                    {{ $app->ai_recommendation ?? 'Indexed' }}
                                </span>
                                <span class="text-[11px] text-slate-500 line-clamp-1 italic mt-0.5">
                                    "{{ $app->ai_summary }}"
                                </span>
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

                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.applications.show', $app) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold transition-colors shadow-xs">
                                    AI Report →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                No candidate applications found matching the filter criteria.
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
