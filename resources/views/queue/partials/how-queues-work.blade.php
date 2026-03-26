<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <div class="px-6 py-5">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">How it works — step by step</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Follow a job from dispatch to completion.</p>
    </div>
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
