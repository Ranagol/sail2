<div
    class="rounded-xl border {{ $failedJobsCount > 0 ? 'border-rose-200 bg-rose-50 dark:border-rose-800 dark:bg-rose-900/20' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900/60' }} px-5 py-5">
    <p
        class="text-xs font-semibold uppercase tracking-wide {{ $failedJobsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">
        Failed jobs</p>
    <p
        class="mt-2 text-4xl font-extrabold tabular-nums {{ $failedJobsCount > 0 ? 'text-rose-700 dark:text-rose-300' : 'text-slate-700 dark:text-slate-300' }}">
        {{ $failedJobsCount }}
    </p>
    <p
        class="mt-1 text-xs {{ $failedJobsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">
        Jobs that threw an exception during handling.</p>
</div>
