<x-demo-layout>
    <div class="w-full space-y-6">

        {{-- Header --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-600 px-6 py-6 text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.2em]">Queue Demo</p>
                <h1 class="mt-2 text-2xl font-bold">Laravel Jobs &amp; Queues with Redis and Elasticache</h1>
                <p class="mt-2 text-m text-purple-100">
                    We simulate here sending 100 emails, by clicling on the 'Dispatch' button. Every
                    email is one job to be executed.These jobs are stored in a Redis queue. We can
                    follow the number of dispatched jobs in the 'Pending in Redis queue'. These jobs
                    will be executed in the Redis queue one by one.

                </p>
            </div>

            <div class="space-y-6 px-6 py-6">

                {{-- Status flash --}}
                <!-- @if (session('status'))
                    <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif -->

                {{-- Live stats --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-violet-200 bg-violet-50 px-5 py-5 dark:border-violet-800 dark:bg-violet-900/20">
                        <p class="text-xs font-semibold uppercase tracking-wide text-violet-600 dark:text-violet-400">Pending in Redis queue</p>
                        <p class="mt-2 text-4xl font-extrabold tabular-nums text-violet-700 dark:text-violet-300">
                            {{ $pendingJobsCount }}
                        </p>
                        <p class="mt-1 text-xs text-violet-600 dark:text-violet-400">Jobs waiting to be picked up by a worker.</p>
                    </div>
                    <div class="rounded-xl border {{ $failedJobsCount > 0 ? 'border-rose-200 bg-rose-50 dark:border-rose-800 dark:bg-rose-900/20' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900/60' }} px-5 py-5">
                        <p class="text-xs font-semibold uppercase tracking-wide {{ $failedJobsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">Failed jobs</p>
                        <p class="mt-2 text-4xl font-extrabold tabular-nums {{ $failedJobsCount > 0 ? 'text-rose-700 dark:text-rose-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $failedJobsCount }}
                        </p>
                        <p class="mt-1 text-xs {{ $failedJobsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">Jobs that threw an exception during handling.</p>
                    </div>
                </div>

                {{-- Dispatch form --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5 dark:border-slate-700 dark:bg-slate-900/60">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Dispatch jobs</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Dispatch 100 <code class="rounded bg-slate-200 px-1 dark:bg-slate-700">SendTestEmailJob</code> jobs to the queue with one click.</p>
                    <form action="{{ route('queue.dispatch') }}" method="POST" class="mt-4 flex flex-wrap items-center gap-3">
                        @csrf
                        <input type="hidden" name="count" value="100">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Dispatches 100 jobs per click.</p>
                        <button type="submit" @disabled($pendingJobsCount > 0) class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-500 disabled:cursor-not-allowed disabled:bg-slate-400 disabled:text-slate-100 dark:hover:bg-violet-400 dark:disabled:bg-slate-600 dark:disabled:text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Dispatch
                        </button>
                        @if ($pendingJobsCount > 0)
                            <p class="text-sm font-medium text-amber-700 dark:text-amber-300">
                                Wait until the current queued jobs finish processing.
                            </p>
                        @endif
                    </form>
                </div>

            </div>
        </section>

        {{-- How queues work --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="px-6 py-5">
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">How it works — step by step</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Follow a job from dispatch to completion.</p>
            </div>



            {{-- Step explanations --}}
            <div class="px-6 py-5">
                <ol class="space-y-3 text-sm text-slate-700 dark:text-slate-300">
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-200 text-xs font-bold text-violet-800 dark:bg-violet-900/60 dark:text-violet-200">1</span>
                        <span>When you click <strong>Dispatch</strong>, the controller calls <code class="rounded bg-slate-100 px-1 dark:bg-slate-700">SendTestEmailJob::dispatch()</code> in a loop. This serialises the job and pushes it onto the <strong>Redis list</strong> immediately — your HTTP response returns before a single email is sent.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-200 text-xs font-bold text-amber-800 dark:bg-amber-900/60 dark:text-amber-200">2</span>
                        <span>Jobs accumulate in Redis and are visible as the <strong>Pending</strong> count above. Nothing processes them until a worker is running.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-200 text-xs font-bold text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">3</span>
                        <span>Start a worker with <code class="rounded bg-slate-100 px-1 dark:bg-slate-700">sail artisan queue:work</code>. It continuously polls Redis, pops one job at a time, and calls <code class="rounded bg-slate-100 px-1 dark:bg-slate-700">handle()</code> on it.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-200 text-xs font-bold text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200">4</span>
                        <span>On success the job is deleted. On exception the worker retries up to <code class="rounded bg-slate-100 px-1 dark:bg-slate-700">$tries</code> times (default 1), then writes the payload + exception to <code class="rounded bg-slate-100 px-1 dark:bg-slate-700">failed_jobs</code> and moves on. Failed jobs can be retried with <code class="rounded bg-slate-100 px-1 dark:bg-slate-700">sail artisan queue:retry all</code>.</span>
                    </li>
                </ol>
            </div>

            {{-- Worker command box (local only) --}}
            @if (app()->environment('local'))
                <div class="mx-6 mb-6 rounded-xl border border-slate-200 bg-slate-900 px-4 py-4 dark:border-slate-700">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Run the worker</p>
                    <code class="block text-sm text-emerald-400">$ sail artisan queue:work</code>
                    <p class="mt-3 mb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Retry all failed jobs</p>
                    <code class="block text-sm text-amber-400">$ sail artisan queue:retry all</code>
                    <p class="mt-3 mb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Flush the failed jobs table</p>
                    <code class="block text-sm text-rose-400">$ sail artisan queue:flush</code>
                </div>
            @endif
        </section>

        {{-- Recent failures --}}
        @if ($recentFailedJobs->isNotEmpty())
            <section class="overflow-hidden rounded-2xl border border-rose-200 bg-white shadow-sm dark:border-rose-800 dark:bg-slate-800">
                <div class="border-b border-rose-100 bg-rose-50 px-6 py-4 dark:border-rose-800 dark:bg-rose-900/20">
                    <h2 class="text-sm font-bold text-rose-700 dark:text-rose-300">Recent failed jobs</h2>
                    <p class="mt-0.5 text-xs text-rose-600 dark:text-rose-400">Last 5 failures recorded in the <code>failed_jobs</code> table.</p>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($recentFailedJobs as $job)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                                    queue: {{ $job->queue }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $job->failed_at }}</span>
                            </div>
                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-900 px-4 py-3 text-xs text-rose-300">{{ str($job->exception)->limit(300) }}</pre>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="mt-8 text-center">
            <a href="/" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</x-demo-layout>
