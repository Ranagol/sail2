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
                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">queue: {{ $job->queue }}</span>
                        <span class="text-xs text-slate-400">{{ $job->failed_at }}</span>
                    </div>
                    <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-900 px-4 py-3 text-xs text-rose-300">{{ str($job->exception)->limit(300) }}</pre>
                </div>
            @endforeach
        </div>
    </section>
@endif
