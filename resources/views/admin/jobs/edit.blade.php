@extends('layouts.admin')

@section('title', 'Edit Job - ' . $job->title)
@section('page_title', 'Edit Job Requisition')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Update Job Details</h2>
                <p class="text-xs text-slate-500">Modify requisition criteria and matching parameters</p>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job opening? All associated applications will also be deleted.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                        Delete Job
                    </button>
                </form>
                <a href="{{ route('admin.jobs.show', $job) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                    ← Back to Pipeline
                </a>
            </div>
        </div>

        <form action="{{ route('admin.jobs.update', $job) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Job Title *</label>
                    <input type="text" name="title" value="{{ old('title', $job->title) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Department *</label>
                    <select name="department" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        @foreach(['Engineering', 'AI / Machine Learning', 'Product & Design', 'DevOps & Cloud', 'Marketing & Growth', 'Sales & Customer Success'] as $dept)
                            <option value="{{ $dept }}" {{ old('department', $job->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Experience Level *</label>
                    <select name="experience_level" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        @foreach(['Entry-Level', 'Mid-Level', 'Senior', 'Lead'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('experience_level', $job->experience_level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Location Type *</label>
                    <select name="location_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        @foreach(['Remote', 'Hybrid', 'Onsite'] as $loc)
                            <option value="{{ $loc }}" {{ old('location_type', $job->location_type) == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">City / Location Hub</label>
                    <input type="text" name="location_city" value="{{ old('location_city', $job->location_city) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Employment Type *</label>
                    <select name="employment_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        @foreach(['Full-time', 'Part-time', 'Contract', 'Internship'] as $type)
                            <option value="{{ $type }}" {{ old('employment_type', $job->employment_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Application Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Compensation -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Salary & Compensation</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Min Annual Salary</label>
                        <input type="number" name="min_salary" value="{{ old('min_salary', $job->min_salary) }}"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Max Annual Salary</label>
                        <input type="number" name="max_salary" value="{{ old('max_salary', $job->max_salary) }}"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', $job->currency) }}"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
            </div>

            <!-- Skills & Matching Criteria -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">Required Skills (AI Match Engine) *</label>
                    <input type="text" name="required_skills_input" 
                           value="{{ old('required_skills_input', is_array($job->required_skills) ? implode(', ', $job->required_skills) : '') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1">Nice-to-Have Skills</label>
                    <input type="text" name="nice_to_have_skills_input" 
                           value="{{ old('nice_to_have_skills_input', is_array($job->nice_to_have_skills) ? implode(', ', $job->nice_to_have_skills) : '') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Descriptions -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Job Description *</label>
                    <textarea name="description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('description', $job->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Candidate Requirements & Qualifications</label>
                    <textarea name="requirements" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('requirements', $job->requirements) }}</textarea>
                </div>
            </div>

            <!-- Status & Publish -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status:</label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="published" {{ $job->status === 'published' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                        <span>Published</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="draft" {{ $job->status === 'draft' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                        <span>Draft</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="radio" name="status" value="closed" {{ $job->status === 'closed' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                        <span>Closed</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.jobs.show', $job) }}" class="px-4 py-2 rounded-xl text-slate-600 text-xs font-bold hover:bg-slate-100">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-brand-600/30 transition-all hover:scale-[1.02]">
                        Save Changes
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection
