<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Careers') - TalentPulse Global Careers</title>
    
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
<body class="h-full flex flex-col font-sans text-slate-800 antialiased bg-slate-50">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('careers.index') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-brand-500/20">
                    ⚡
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 text-lg tracking-tight">TalentPulse</span>
                    <span class="text-xs text-brand-600 font-bold ml-1">Careers</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors">
                    Recruiter Sign In
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-brand-600 text-white text-xs font-bold transition-all shadow-xs">
                    Admin Portal ↗
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-8 text-center text-xs text-slate-400">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-center gap-2 mb-2 font-bold text-slate-700">
                <span>TalentPulse AI</span> • <span>Intelligent Applicant Tracking System</span>
            </div>
            <p>Designed for modern engineering teams and accelerated technical hiring.</p>
        </div>
    </footer>

</body>
</html>
