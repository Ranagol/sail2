<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} — Demo Overview</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

        {{-- Top nav --}}
        <nav class="border-b border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
                <span class="text-base font-bold tracking-tight text-slate-800 dark:text-slate-100">
                    {{ config('app.name', 'Laravel') }}
                    <span class="ml-2 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:bg-slate-700 dark:text-slate-400">demo playground</span>
                </span>
                <div class="flex items-center gap-3 text-sm">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-1.5 font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-slate-900 px-4 py-1.5 font-medium text-white transition hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-5xl px-6 py-12">

            {{-- Hero --}}
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-4xl">Laravel Feature Playground</h1>
                <p class="mt-3 text-base text-slate-500 dark:text-slate-400">
                    A hands-on demo app covering the core Laravel features below.<br>
                    Click any card to explore and experiment.
                </p>
            </div>

            {{-- Demo cards --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                {{-- Session --}}
                <a href="{{ route('session.demo') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Session Demo</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Write, read, and delete a value in Laravel's server-side session. All actions happen on a single URL using the POST–Redirect–GET pattern.
                        </p>
                        <ul class="mt-4 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>Session store & retrieval</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>Flash messages</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>PRG redirect pattern</li>
                        </ul>
                        <div class="mt-5 flex items-center text-xs font-semibold text-sky-600 transition group-hover:text-sky-500 dark:text-sky-400">
                            Open demo <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                {{-- Redis Cache --}}
                <a href="{{ route('redis.demo') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Redis Cache Demo</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            See the speed gap between a cold MySQL query and a warm Redis cache hit. Timings are measured live and a speedup factor is shown.
                        </p>
                        <ul class="mt-4 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>Cache::remember() pattern</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>Cold vs warm request timing</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>Redis as cache driver</li>
                        </ul>
                        <div class="mt-5 flex items-center text-xs font-semibold text-orange-600 transition group-hover:text-orange-500 dark:text-orange-400">
                            Open demo <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                {{-- Queue --}}
                <a href="{{ route('queue.demo') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">Queue Demo</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Dispatch email jobs onto the Redis queue and watch them pile up. Explains the full lifecycle: dispatch → queue → worker → done (or failed).
                        </p>
                        <ul class="mt-4 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>Queued jobs with ShouldQueue</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>Pending & failed job counts</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>queue:work / queue:retry</li>
                        </ul>
                        <div class="mt-5 flex items-center text-xs font-semibold text-violet-600 transition group-hover:text-violet-500 dark:text-violet-400">
                            Open demo <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                {{-- File Upload --}}
                <a href="{{ route('files.index') }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 px-6 py-5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-lg font-bold text-white">File Upload Demo</h2>
                    </div>
                    <div class="flex flex-1 flex-col px-6 py-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Upload files to S3-compatible storage, list them, download them, and delete them. Requires authentication to protect the upload area.
                        </p>
                        <ul class="mt-4 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>S3 / local disk storage</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Upload, download & delete</li>
                            <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Auth-protected routes</li>
                        </ul>
                        <div class="mt-5 flex items-center text-xs font-semibold text-emerald-600 transition group-hover:text-emerald-500 dark:text-emerald-400">
                            Open demo <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            </div>
        </main>
    </body>
</html>


