@extends('layouts.public')

@section('title', $job->title . ' - Apply Now')

@section('content')
<div class="py-10 sm:py-14">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <div class="mb-6 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('careers.index') }}" class="hover:text-brand-600">Careers</a>
            <span>/</span>
            <span class="text-slate-700 font-semibold">{{ $job->department }}</span>
            <span>/</span>
            <span class="text-slate-900 font-bold truncate max-w-xs">{{ $job->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left: Job Information -->
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $job->department }}
                        </span>
                        <span class="text-xs text-slate-500">{{ $job->employment_type }}</span>
                        <span class="text-xs text-slate-300">•</span>
                        <span class="text-xs text-slate-500">{{ $job->location_type }} {{ $job->location_city ? "({$job->location_city})" : '' }}</span>
                        <span class="text-xs text-slate-300">•</span>
                        <span class="text-xs text-slate-500 font-medium">Experience: {{ $job->experience_level }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $job->title }}
                    </h1>

                    <div class="inline-block px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800">
                        💰 Offering: {{ $job->salary_range_formatted }} / year
                    </div>

                    <!-- Required Skills Stack -->
                    @if(!empty($job->required_skills))
                        <div class="pt-4 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Core Tech Stack & Prerequisites:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($job->required_skills as $sk)
                                    <span class="text-xs bg-brand-50 text-brand-800 font-semibold px-3 py-1 rounded-lg border border-brand-200/60">{{ $sk }}</span>
                                @endforeach
                                @if(!empty($job->nice_to_have_skills))
                                    @foreach($job->nice_to_have_skills as $sk)
                                        <span class="text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg border border-slate-200 font-medium">+ {{ $sk }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Job Description -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                    <h2 class="text-base font-bold text-slate-900 uppercase tracking-wider">Role Overview</h2>
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line space-y-4">
{{ $job->description }}
                    </div>
                </div>

                <!-- Qualifications -->
                @if($job->requirements)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                        <h2 class="text-base font-bold text-slate-900 uppercase tracking-wider">Candidate Profile & Qualifications</h2>
                        <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line space-y-4">
{{ $job->requirements }}
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right: Application Submission Form -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-lg sticky top-20">
                <div class="mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Apply for this Role</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Submit your resume for immediate automated AI screening</p>
                </div>

                @if($errors->any())
                    <div class="p-3 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('careers.apply', $job->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 555-0199"
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Years of Exp *</label>
                            <input type="number" step="0.5" min="0" max="40" name="years_of_experience" value="{{ old('years_of_experience', '4') }}" required
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Current Role</label>
                            <input type="text" name="current_role" value="{{ old('current_role') }}" placeholder="Software Engineer"
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Current Company</label>
                            <input type="text" name="current_company" value="{{ old('current_company') }}" placeholder="Tech Corp"
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">LinkedIn Profile</label>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/..."
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Portfolio Website</label>
                            <input type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://mywork.com"
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">GitHub Profile</label>
                            <input type="url" name="github_url" value="{{ old('github_url') }}" placeholder="https://github.com/..."
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Key Skills (Comma separated)</label>
                        <input type="text" name="skills_input" value="{{ old('skills_input') }}" placeholder="e.g. Laravel, PHP, PostgreSQL, Vue, Docker"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Resume Upload or Text -->
                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-800 mb-1">Upload Resume (PDF, DOCX, TXT)</label>
                        <input type="file" name="resume_file" accept=".pdf,.doc,.docx,.txt"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Or Paste Resume / Experience Summary</label>
                        <textarea name="resume_text" rows="3" placeholder="Paste resume bullet points, key projects, and responsibilities..."
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">{{ old('resume_text') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Note to Hiring Team</label>
                        <textarea name="cover_letter" rows="2" placeholder="Brief note on why you are excited for this role..."
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500">{{ old('cover_letter') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.01]">
                        Submit Application 🚀
                    </button>
                    
                    <p class="text-[10px] text-slate-400 text-center">
                        ⚡ TalentPulse AI automatically parses and matches resumes in seconds.
                    </p>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
