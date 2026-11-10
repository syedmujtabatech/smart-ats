<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TalentPulse AI ATS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans antialiased bg-slate-950">

    <div class="w-full max-w-4xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
        
        <!-- Left Side: Branding & AI Highlights -->
        <div class="p-8 sm:p-12 bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-900 border-b lg:border-b-0 lg:border-r border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-brand-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white font-extrabold text-xl">
                        ⚡
                    </div>
                    <div class="text-white font-extrabold text-xl tracking-tight">
                        TalentPulse <span class="text-xs px-2 py-0.5 rounded-full bg-brand-500/20 text-brand-300 font-bold border border-brand-500/30">AI</span>
                    </div>
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight mb-4">
                    Next-Gen ATS with <span class="bg-gradient-to-r from-indigo-400 to-cyan-300 bg-clip-text text-transparent">AI Resume Intelligence</span>
                </h2>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Automate candidate screening, rank resumes semantically against job descriptions, and streamline interview pipelines in minutes.
                </p>

                <div class="space-y-3.5">
                    <div class="flex items-center gap-3 text-xs text-slate-300">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold">✓</div>
                        <span>Automated 0-100% AI Resume Match Scoring</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-300">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold">✓</div>
                        <span>Skill Gap Identification & Strengths Breakdown</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-300">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold">✓</div>
                        <span>Interactive Multi-Stage Kanban Pipeline</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-500">
                <span>Enterprise Grade ATS</span>
                <a href="{{ route('careers.index') }}" class="text-brand-400 hover:text-brand-300 font-medium">View Public Career Page ↗</a>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="p-8 sm:p-12 bg-slate-900 flex flex-col justify-center">
            
            <div class="mb-6">
                <h3 class="text-xl font-bold text-white">Sign In to Dashboard</h3>
                <p class="text-xs text-slate-400 mt-1">Access candidate evaluations and recruitment analytics</p>
            </div>

            <!-- One-Click Demo Login Box (For Recruiters/Evaluators) -->
            <div class="mb-6 p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-center">
                <div class="text-xs font-semibold text-indigo-300 mb-2">⚡ Quick Recruiter Demo Access</div>
                <form action="{{ route('login.demo') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                        <span>🚀</span> Explore as Demo Recruiter
                    </button>
                </form>
            </div>

            <div class="relative my-4">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-800"></div></div>
                <div class="relative flex justify-center text-[11px] uppercase tracking-wider text-slate-500 bg-slate-900 px-3">or login with credentials</div>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@talentpulse.io') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">Password</label>
                    <input type="password" name="password" value="password" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-brand-600 focus:ring-brand-500">
                        <span>Remember me</span>
                    </label>
                    <span class="text-slate-500">Default: password</span>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl border border-slate-700 transition-colors">
                    Sign In
                </button>
            </form>
        </div>

    </div>

</body>
</html>
