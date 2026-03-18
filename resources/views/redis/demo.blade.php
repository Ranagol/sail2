<x-guest-layout>
    <div class="w-full space-y-6">

        {{-- Header card --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500 px-6 py-6 text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.2em]">Redis Cache Demo</p>
                <h1 class="mt-2 text-2xl font-bold">See the speed difference yourself</h1>
                <p class="mt-2 text-sm text-orange-100">
                    This page fetches <strong>{{ $usersCount }} users</strong> twice —
                    first hitting the database, then reading from the Redis cache.
                    Both timings are measured and shown below.
                </p>
            </div>

            <div class="space-y-6 px-6 py-6">

                {{-- Timing comparison cards --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5 dark:border-slate-700 dark:bg-slate-900/60">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10M20 7v10M4 12h16" />
                                </svg>
                            </span>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">1st request — database</p>
                        </div>
                        <p class="mt-3 text-3xl font-extrabold tabular-nums text-slate-900 dark:text-slate-100">
                            {{ $firstDurationMs }}<span class="ml-1 text-base font-semibold text-slate-500 dark:text-slate-400">ms</span>
                        </p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Cache was cleared — data read directly from MySQL.</p>
                    </div>

                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-5 dark:border-emerald-800 dark:bg-emerald-900/20">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">2nd request — redis cache</p>
                        </div>
                        <p class="mt-3 text-3xl font-extrabold tabular-nums text-emerald-700 dark:text-emerald-300">
                            {{ $secondDurationMs }}<span class="ml-1 text-base font-semibold text-emerald-500 dark:text-emerald-400">ms</span>
                        </p>
                        <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">Data served entirely from Redis — no database query.</p>
                    </div>

                </div>

                {{-- Speedup banner --}}
                @if ($speedupFactor !== null && $speedupFactor > 1)
                    <div class="flex items-center gap-4 rounded-xl border border-amber-300 bg-amber-50 px-5 py-4 dark:border-amber-700 dark:bg-amber-900/20">
                        <span class="text-3xl">⚡</span>
                        <div>
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-300">
                                Redis was <span class="text-lg">{{ $speedupFactor }}×</span> faster than MySQL on this request.
                            </p>
                            <p class="mt-0.5 text-xs text-amber-700 dark:text-amber-400">
                                In production, with high traffic, this multiplier scales your app significantly.
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Explanation section --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5 dark:border-slate-700 dark:bg-slate-900/60">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">How it works</p>
                    <ol class="mt-3 space-y-2 text-sm text-slate-700 dark:text-slate-300">
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-300 text-xs font-bold text-slate-700 dark:bg-slate-700 dark:text-slate-200">1</span>
                            <span>The cache for <code class="rounded bg-slate-200 px-1 dark:bg-slate-700">users.all</code> is <strong>cleared</strong> to simulate a cold start.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-300 text-xs font-bold text-slate-700 dark:bg-slate-700 dark:text-slate-200">2</span>
                            <span><code class="rounded bg-slate-200 px-1 dark:bg-slate-700">Cache::remember()</code> misses the cache, so Laravel hits MySQL and stores the result in Redis for 60 seconds.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-300 text-xs font-bold text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200">3</span>
                            <span>The second call hits the cache — Redis returns all {{ $usersCount }} users <strong>without touching the database</strong>, in a fraction of the time.</span>
                        </li>
                    </ol>
                </div>

                {{-- Reload button --}}
                <div class="text-right">
                    <a href="{{ route('redis.demo') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-rose-500 dark:hover:bg-rose-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0115.447-3.5M20 15a9 9 0 01-15.447 3.5" />
                        </svg>
                        Run benchmark again
                    </a>
                </div>

            </div>
        </section>

    </div>
</x-guest-layout>
