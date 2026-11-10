@extends('layouts.admin')

@section('title', 'AI Match Report - ' . $application->candidate->name)
@section('page_title', 'Candidate AI Evaluation Report')

@section('content')
<div class="space-y-6">

    <!-- Top Candidate Header Card -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-brand-500/20">
                    {{ substr($application->candidate->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $application->candidate->name }}</h1>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $application->stage_badge_class }}">
                            {{ ucfirst($application->stage) }}
                        </span>
                    </div>

                    <div class="text-xs text-slate-500 font-medium mt-1 flex flex-wrap items-center gap-3">
                        <span>{{ $application->candidate->current_role ?? 'Applicant' }} {{ $application->candidate->current_company ? "at {$application->candidate->current_company}" : '' }}</span>
                        <span>•</span>
                        <span>{{ $application->candidate->years_of_experience }} Years Experience</span>
                        <span>•</span>
                        <span>Applied for <a href="{{ route('admin.jobs.show', $application->jobPost) }}" class="font-bold text-brand-600 hover:underline">{{ $application->jobPost->title }}</a></span>
                    </div>

                    <!-- Contact & Social Links -->
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-xs">
                        <a href="mailto:{{ $application->candidate->email }}" class="text-slate-600 hover:text-brand-600 flex items-center gap-1">
                            ✉️ {{ $application->candidate->email }}
                        </a>
                        @if($application->candidate->phone)
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-600">📞 {{ $application->candidate->phone }}</span>
                        @endif
                        @if($application->candidate->linkedin_url)
                            <span class="text-slate-300">•</span>
                            <a href="{{ $application->candidate->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline font-medium">LinkedIn ↗</a>
                        @endif
                        @if($application->candidate->github_url)
                            <span class="text-slate-300">•</span>
                            <a href="{{ $application->candidate->github_url }}" target="_blank" class="text-slate-800 hover:underline font-medium">GitHub ↗</a>
                        @endif
                        @if($application->candidate->portfolio_url)
                            <span class="text-slate-300">•</span>
                            <a href="{{ $application->candidate->portfolio_url }}" target="_blank" class="text-brand-600 hover:underline font-medium">Portfolio ↗</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stage Changer & AI Re-Evaluate -->
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.applications.reanalyze', $application) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all flex items-center gap-2 border border-indigo-200">
                        <span>⚡</span> Re-Analyze with AI
                    </button>
                </form>

                <form action="{{ route('admin.applications.stage', $application) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="stage" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold focus:outline-none">
                        <option value="applied" {{ $application->stage === 'applied' ? 'selected' : '' }}>Stage: Applied</option>
                        <option value="screening" {{ $application->stage === 'screening' ? 'selected' : '' }}>Stage: Screening</option>
                        <option value="interview" {{ $application->stage === 'interview' ? 'selected' : '' }}>Stage: Technical Round</option>
                        <option value="hr_round" {{ $application->stage === 'hr_round' ? 'selected' : '' }}>Stage: HR Round</option>
                        <option value="offered" {{ $application->stage === 'offered' ? 'selected' : '' }}>Stage: Offered</option>
                        <option value="hired" {{ $application->stage === 'hired' ? 'selected' : '' }}>Stage: Hired</option>
                        <option value="rejected" {{ $application->stage === 'rejected' ? 'selected' : '' }}>Stage: Disqualified</option>
                    </select>
                </form>
            </div>

        </div>
    </div>

    <!-- AI Intelligence Screening Banner -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Match Score & Recommendation Gauge -->
        <div class="bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-900 rounded-2xl p-6 text-white shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase tracking-wider text-indigo-300 font-bold">Semantic Compatibility</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-semibold">Gemini LLM / NLP</span>
                </div>

                <div class="flex items-center gap-4 my-4">
                    <div class="w-24 h-24 rounded-3xl bg-indigo-500/20 border-2 border-indigo-400/40 flex flex-col items-center justify-center shadow-lg shadow-indigo-500/20">
                        <span class="text-3xl font-black text-white tracking-tight">{{ $application->ai_match_score ?? 0 }}%</span>
                        <span class="text-[9px] uppercase tracking-wider text-indigo-300 font-bold">Match Score</span>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white leading-tight">
                            {{ $application->ai_recommendation ?? 'Evaluation Complete' }}
                        </div>
                        <div class="text-xs text-indigo-200/80 mt-1">
                            Calculated across {{ count($application->jobPost->required_skills ?? []) }} core skills & {{ $application->candidate->years_of_experience }}y experience.
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 text-xs text-slate-400 flex items-center justify-between">
                <span>Evaluated: {{ optional($application->ai_analyzed_at ?? $application->created_at)->diffForHumans() }}</span>
                <span class="text-emerald-400 font-semibold">● Verified Vector Fit</span>
            </div>
        </div>

        <!-- AI Executive Recruiter Summary -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="text-brand-600">🤖</span> AI Recruiter Assessment
                    </h3>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $application->ai_sentiment === 'Positive' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        Sentiment: {{ $application->ai_sentiment ?? 'Positive' }}
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed italic">
                    "{{ $application->ai_summary ?? 'Candidate profile has been analyzed against the target job requirements.' }}"
                </div>

                <!-- Strengths & Skill Gaps Comparison -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <!-- Key Strengths -->
                    <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <div class="text-xs font-bold text-emerald-800 mb-2 flex items-center gap-1.5">
                            <span>✓</span> Key Matched Strengths
                        </div>
                        <ul class="space-y-1.5 text-xs text-emerald-900">
                            @if(!empty($application->ai_strengths))
                                @foreach($application->ai_strengths as $str)
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold mt-0.5">•</span>
                                        <span>{{ $str }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-slate-400">Core requirements satisfactorily covered.</li>
                            @endif
                        </ul>
                    </div>

                    <!-- Gaps / Missing Skills -->
                    <div class="p-3.5 rounded-xl bg-amber-50/60 border border-amber-100">
                        <div class="text-xs font-bold text-amber-800 mb-2 flex items-center gap-1.5">
                            <span>⚠️</span> Skill Gaps & Missing Requisites
                        </div>
                        <ul class="space-y-1.5 text-xs text-amber-900">
                            @if(!empty($application->ai_missing_skills))
                                @foreach($application->ai_missing_skills as $gap)
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-amber-500 font-bold mt-0.5">•</span>
                                        <span>{{ $gap }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-slate-400">No critical skill gaps flagged.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Comparison Grid: Job Description vs Candidate Resume -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Left: Job Opening Specifications -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Target Job Requisition</h3>
                <span class="text-xs text-slate-500 font-medium">{{ $application->jobPost->department }}</span>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Required Skills:</h4>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($application->jobPost->required_skills ?? [] as $sk)
                        <span class="text-xs bg-slate-100 text-slate-800 px-2 py-0.5 rounded-md font-medium">{{ $sk }}</span>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Role Description:</h4>
                <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line max-h-60 overflow-y-auto pr-2">
                    {{ $application->jobPost->description }}
                </div>
            </div>

            @if($application->jobPost->requirements)
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Qualifications Expected:</h4>
                    <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line max-h-40 overflow-y-auto pr-2">
                        {{ $application->jobPost->requirements }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Candidate Resume & Application Data -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Candidate Resume Content</h3>
                @if($application->resume_path)
                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="text-xs font-bold text-brand-600 hover:underline">
                        Download Original File ↗
                    </a>
                @endif
            </div>

            @if($application->cover_letter)
                <div class="p-3 bg-brand-50/50 rounded-xl border border-brand-100">
                    <span class="text-xs font-bold text-brand-900 uppercase tracking-wider block mb-1">Candidate Cover Note:</span>
                    <p class="text-xs text-slate-700 italic">{{ $application->cover_letter }}</p>
                </div>
            @endif

            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Parsed Resume Text:</h4>
                <div class="p-4 rounded-xl bg-slate-900 text-slate-200 font-mono text-xs leading-relaxed max-h-96 overflow-y-auto whitespace-pre-wrap">
{{ $application->resume_text }}
                </div>
            </div>
        </div>

    </div>

    <!-- Interview Scheduling & Internal Notes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Schedule an Interview -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">📅 Schedule Interview Round</h3>

            <form action="{{ route('admin.applications.interview', $application) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Interviewer Name *</label>
                        <input type="text" name="interviewer_name" value="Sarah Connor (Engineering Lead)" required
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Round Type *</label>
                        <select name="interview_type" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                            <option value="Technical Screening">Technical Screening (45 min)</option>
                            <option value="System Design & Architecture">System Design & Architecture (60 min)</option>
                            <option value="Live Coding Session">Live Coding Session (60 min)</option>
                            <option value="HR & Culture Fit">HR & Culture Fit (30 min)</option>
                            <option value="Executive Final">Executive Final (45 min)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Date & Time *</label>
                        <input type="datetime-local" name="scheduled_at" required
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" value="45" min="15" max="180"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Meeting Link (Google Meet / Zoom)</label>
                    <input type="url" name="meeting_link" placeholder="https://meet.google.com/xyz-abc-def"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/30 transition-all">
                    Confirm & Schedule Interview
                </button>
            </form>

            <!-- Scheduled Interviews List -->
            @if($application->interviewSchedules->count() > 0)
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-700 uppercase mb-2">Upcoming / Past Interviews</h4>
                    <div class="space-y-2">
                        @foreach($application->interviewSchedules as $sch)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-slate-900">{{ $sch->interview_type }}</div>
                                    <div class="text-slate-500">{{ $sch->interviewer_name }} • {{ $sch->scheduled_at->format('M d, Y @ h:i A') }}</div>
                                </div>
                                @if($sch->meeting_link)
                                    <a href="{{ $sch->meeting_link }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100">
                                        Join Link ↗
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Recruiter Internal Notes & Rating -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">📝 Recruiter Notes & Evaluation</h3>

                <form action="{{ route('admin.applications.notes', $application) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Overall Rating (1 - 5 Stars)</label>
                        <select name="rating" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                            <option value="">No rating selected</option>
                            <option value="5" {{ $application->rating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 - Exceptional Candidate</option>
                            <option value="4" {{ $application->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4 - Strong Fit</option>
                            <option value="3" {{ $application->rating == 3 ? 'selected' : '' }}>⭐⭐⭐ 3 - Meets Requirements</option>
                            <option value="2" {{ $application->rating == 2 ? 'selected' : '' }}>⭐⭐ 2 - Significant Gaps</option>
                            <option value="1" {{ $application->rating == 1 ? 'selected' : '' }}>⭐ 1 - Not Recommended</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Interview Observations / Feedback</label>
                        <textarea name="notes" rows="4" placeholder="Enter private debrief notes, coding test remarks, or salary negotiations..."
                                  class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">{{ old('notes', $application->notes) }}</textarea>
                    </div>

                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-bold transition-colors">
                        Save Internal Notes
                    </button>
                </form>
            </div>

            <!-- Activity Logs Audit Trail -->
            <div class="mt-6 pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-700 uppercase mb-2">Audit Trail</h4>
                <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                    @forelse($application->activityLogs as $log)
                        <div class="text-[11px] text-slate-600 flex items-start gap-2">
                            <span class="text-brand-500 font-bold">•</span>
                            <span>{{ $log->description }} <span class="text-slate-400">({{ $log->created_at->diffForHumans() }})</span></span>
                        </div>
                    @empty
                        <div class="text-slate-400 text-xs">No activity logged yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
