<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ClassiQ – AI-Powered Learning Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-50 antialiased">
    <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-400 text-slate-950 font-black">
                    CQ
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-sm font-semibold tracking-wide uppercase text-emerald-300">
                        ClassiQ
                    </span>
                    <span class="text-xs text-slate-400">
                        Joyful Bilingual Learning
                    </span>
                </div>
            </div>

            <div class="hidden items-center gap-6 text-sm font-medium text-slate-200 md:flex">
                <a href="#for-whom" class="hover:text-emerald-300">Who it’s for</a>
                <a href="#features" class="hover:text-emerald-300">Features</a>
                <a href="#teachers" class="hover:text-emerald-300">For Teachers</a>
                <a href="#parents" class="hover:text-emerald-300">For Parents</a>
            </div>

            <div class="flex items-center gap-3">
                <button class="hidden rounded-full border border-slate-700 px-4 py-1.5 text-sm font-medium text-slate-100 hover:border-emerald-400 hover:text-emerald-300 md:inline-block">
                    Log in
                </button>
                <button class="rounded-full bg-emerald-400 px-4 py-1.5 text-sm font-semibold text-slate-950 shadow-lg shadow-emerald-500/40 hover:bg-emerald-300">
                    Get Started
                </button>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot ?? '' }}
    </main>

    <footer class="mt-16 border-t border-slate-800 bg-slate-950">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-200">ClassiQ – Learning for Every Student</p>
                <p class="mt-1 text-xs text-slate-500">
                    Bilingual, inclusive, AI‑ready learning platform for Bangladeshi and special‑needs students.
                </p>
            </div>
            <div class="flex flex-wrap gap-4 text-xs text-slate-400">
                <a href="#features" class="hover:text-emerald-300">Features</a>
                <a href="#teachers" class="hover:text-emerald-300">For Teachers</a>
                <a href="#parents" class="hover:text-emerald-300">For Parents & Schools</a>
                <a href="#contact" class="hover:text-emerald-300">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>
