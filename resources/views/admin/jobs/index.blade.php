@extends('layouts.admin')

@section('title', 'Job Openings')
@section('page_title', 'Job Openings & Requisitions')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search Filters -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <form action="{{ route('admin.jobs.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by role or department..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="department" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published (Active)</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            @if(request()->anyFilled(['search', 'department', 'status']))
                <a href="{{ route('admin.jobs.index') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold px-2">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-all hover:scale-[1.02] whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Job Opening
        </a>

    </div>

    <!-- Job Cards List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jobs as $job)
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $job->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($job->status) }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">{{ $job->department }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1">
                        <a href="{{ route('admin.jobs.show', $job) }}">{{ $job->title }}</a>
                    </h3>

                    <div class="flex items-center gap-2 text-xs text-slate-500 mt-2">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $job->location_type }} {{ $job->location_city ? "({$job->location_city})" : '' }}
                        </span>
                        <span>•</span>
                        <span>{{ $job->experience_level }}</span>
                    </div>

                    <div class="text-xs font-semibold text-slate-700 mt-2">
                        💰 {{ $job->salary_range_formatted }}
                    </div>

                    <!-- Skills Tags Preview -->
                    @if(!empty($job->required_skills))
                        <div class="flex flex-wrap gap-1.5 mt-4">
                            @foreach(array_slice($job->required_skills, 0, 4) as $skill)
                                <span class="text-[11px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-medium">{{ $skill }}</span>
                            @endforeach
                            @if(count($job->required_skills) > 4)
                                <span class="text-[11px] text-slate-400 px-1 py-0.5">+{{ count($job->required_skills) - 4 }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Footer with Applicant Count & Links -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs">
                        <span class="font-bold text-brand-600 text-sm">{{ $job->applications_count }}</span>
                        <span class="text-slate-500 font-medium">candidates</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('careers.show', $job->slug) }}" target="_blank" title="View Public Career Page" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('admin.jobs.edit', $job) }}" title="Edit Job" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <a href="{{ route('admin.jobs.show', $job) }}" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-semibold transition-colors">
                            Review ({{ $job->applications_count }})
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-200/80">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-brand-600 flex items-center justify-center mx-auto text-xl mb-3">📋</div>
                <h3 class="text-base font-bold text-slate-900">No Job Openings Found</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">Start hiring by publishing your first job requisition.</p>
                <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-brand-600 text-white text-xs font-bold rounded-xl shadow-sm">
                    Create New Job
                </a>
            </div>
        @endforelse
    </div>

    <div>
        {{ $jobs->links() }}
    </div>

</div>
@endsection
