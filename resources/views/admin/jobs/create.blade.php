@extends('layouts.admin')

@section('title', 'Post New Job')
@section('page_title', 'Create Job Requisition')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">New Job Opening Details</h2>
                <p class="text-xs text-slate-500">Provide job specifications, required stack, and candidate evaluation criteria</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                ← Back to Jobs
            </a>
        </div>

        <form action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Job Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Senior Full-Stack Engineer (AI & Cloud)" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Department *</label>
                    <select name="department" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="AI / Machine Learning" {{ old('department') == 'AI / Machine Learning' ? 'selected' : '' }}>AI / Machine Learning</option>
                        <option value="Product & Design" {{ old('department') == 'Product & Design' ? 'selected' : '' }}>Product & Design</option>
                        <option value="DevOps & Cloud" {{ old('department') == 'DevOps & Cloud' ? 'selected' : '' }}>DevOps & Cloud</option>
                        <option value="Marketing & Growth" {{ old('department') == 'Marketing & Growth' ? 'selected' : '' }}>Marketing & Growth</option>
                        <option value="Sales & Customer Success" {{ old('department') == 'Sales & Customer Success' ? 'selected' : '' }}>Sales & Customer Success</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Experience Level *</label>
                    <select name="experience_level" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="Entry-Level" {{ old('experience_level') == 'Entry-Level' ? 'selected' : '' }}>Entry-Level (0-2 yrs)</option>
                        <option value="Mid-Level" {{ old('experience_level', 'Mid-Level') == 'Mid-Level' ? 'selected' : '' }}>Mid-Level (3-5 yrs)</option>
                        <option value="Senior" {{ old('experience_level') == 'Senior' ? 'selected' : '' }}>Senior (5-8 yrs)</option>
                        <option value="Lead" {{ old('experience_level') == 'Lead' ? 'selected' : '' }}>Lead / Principal (8+ yrs)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Location Type *</label>
                    <select name="location_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="Remote" {{ old('location_type') == 'Remote' ? 'selected' : '' }}>Remote</option>
                        <option value="Hybrid" {{ old('location_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="Onsite" {{ old('location_type') == 'Onsite' ? 'selected' : '' }}>Onsite</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">City / Location Hub</label>
                    <input type="text" name="location_city" value="{{ old('location_city') }}" placeholder="e.g. San Francisco, CA or Worldwide"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Employment Type *</label>
                    <select name="employment_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Application Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Compensation -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Salary & Compensation</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Min Annual Salary</label>
                        <input type="number" name="min_salary" value="{{ old('min_salary') }}" placeholder="e.g. 90000"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Max Annual Salary</label>
                        <input type="number" name="max_salary" value="{{ old('max_salary') }}" placeholder="e.g. 130000"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', 'USD') }}" placeholder="USD"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
            </div>

            <!-- Skills & Matching Criteria -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Required Skills (For AI Match Engine) *</label>
                        <span class="text-[11px] text-slate-400">Separate with commas</span>
                    </div>
                    <input type="text" name="required_skills_input" value="{{ old('required_skills_input') }}" 
                           placeholder="e.g. Laravel, PHP, PostgreSQL, REST APIs, Vue.js, Docker"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-500 mt-1">Our AI scoring model uses these exact competencies to calculate semantic fit and highlight candidate gaps.</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Nice-to-Have Skills (Bonus Weight)</label>
                        <span class="text-[11px] text-slate-400">Separate with commas</span>
                    </div>
                    <input type="text" name="nice_to_have_skills_input" value="{{ old('nice_to_have_skills_input') }}" 
                           placeholder="e.g. Redis, TailwindCSS, AWS, CI/CD, Microservices"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Descriptions -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Job Description *</label>
                    <textarea name="description" rows="5" required placeholder="Describe the role mission, day-to-day responsibilities, and team culture..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Candidate Requirements & Qualifications</label>
                    <textarea name="requirements" rows="4" placeholder="Detail education, years of experience, architectural background, or specific project history..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('requirements') }}</textarea>
                </div>
            </div>

            <!-- Status & Publish -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Publish Status:</label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="published" checked class="text-brand-600 focus:ring-brand-500">
                        <span>Published (Live on Career Portal)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="draft" class="text-brand-600 focus:ring-brand-500">
                        <span>Draft</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2 rounded-xl text-slate-600 text-xs font-bold hover:bg-slate-100">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                        Publish Requisition
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection
