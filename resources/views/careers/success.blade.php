@extends('layouts.public')

@section('title', 'Application Submitted!')

@section('content')
<div class="py-16 sm:py-24">
    <div class="max-w-xl mx-auto px-4 text-center">
        
        <div class="w-16 h-16 rounded-3xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg shadow-emerald-500/20">
            ✓
        </div>

        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
            Application Received & Indexed
        </span>

        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3 mb-3">
            Thank You, {{ $application->candidate->name }}!
        </h1>

        <p class="text-sm text-slate-600 leading-relaxed mb-6">
            Your application for <span class="font-bold text-slate-800">{{ $application->jobPost->title }}</span> has been submitted to the recruitment team. Our AI engine has already parsed your profile and notified the hiring managers.
        </p>

        <!-- Summary Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs text-left text-xs space-y-2 mb-8">
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Applicant Name</span>
                <span class="font-bold text-slate-900">{{ $application->candidate->name }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Email Address</span>
                <span class="font-bold text-slate-900">{{ $application->candidate->email }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Role Applied</span>
                <span class="font-bold text-brand-600">{{ $application->jobPost->title }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-slate-500">Screening Status</span>
                <span class="font-bold text-emerald-600">● AI Screening Complete</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('careers.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors">
                ← View More Open Roles
            </a>
            <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/30 transition-all">
                Recruiter Portal (Demo View) ↗
            </a>
        </div>

    </div>
</div>
@endsection
