@extends('layouts.public')

@section('title', 'Explore Open Roles')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Hero Banner -->
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200/60">
                We're Hiring Across Global Tech Hubs
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                Build the Future with Us
            </h1>
            <p class="text-sm text-slate-500 leading-relaxed">
                Explore high-impact positions across Engineering, AI, Cloud Infrastructure, and Product. Our automated screening ensures your application gets evaluated fast.
            </p>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs">
            <form action="{{ route('careers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="relative sm:col-span-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by role or keyword..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <div>
                    <select name="department" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:ring-2 focus:ring-brand-500">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="location_type" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:ring-2 focus:ring-brand-500">
                        <option value="">All Locations (Remote / Onsite)</option>
                        <option value="Remote" {{ request('location_type') == 'Remote' ? 'selected' : '' }}>Remote</option>
                        <option value="Hybrid" {{ request('location_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="Onsite" {{ request('location_type') == 'Onsite' ? 'selected' : '' }}>Onsite</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Open Roles Cards -->
        <div class="space-y-4">
            @forelse($jobs as $job)
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $job->department }}
                            </span>
                            <span class="text-xs text-slate-500">{{ $job->employment_type }}</span>
                            <span class="text-xs text-slate-300">•</span>
                            <span class="text-xs text-slate-500">{{ $job->location_type }} {{ $job->location_city ? "({$job->location_city})" : '' }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                            <a href="{{ route('careers.show', $job->slug) }}">{{ $job->title }}</a>
                        </h3>

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($job->description), 180) }}
                        </p>

                        <!-- Skills Tags -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach(array_slice($job->required_skills ?? [], 0, 5) as $skill)
                                <span class="text-[11px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-medium">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row md:flex-col items-start md:items-end justify-between gap-3 shrink-0">
                        <div class="text-right">
                            <span class="text-xs text-slate-400 block">Compensation</span>
                            <span class="text-sm font-bold text-slate-900">{{ $job->salary_range_formatted }}</span>
                        </div>

                        <a href="{{ route('careers.show', $job->slug) }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02] text-center">
                            View Role & Apply →
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80">
                    <div class="text-2xl mb-2">🔍</div>
                    <h3 class="text-base font-bold text-slate-900">No Openings Match Your Query</h3>
                    <p class="text-xs text-slate-500 mt-1">Try clearing your filters or check back soon.</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $jobs->links() }}
        </div>

    </div>
</div>
@endsection
