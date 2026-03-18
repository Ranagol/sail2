<x-guest-layout>
    @php
        $hasValue = $sessionValue !== null;
    @endphp

    <div class="w-full space-y-6">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-600 px-6 py-6 text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.2em]">Session Demo</p>
                <h1 class="mt-2 text-2xl font-bold">Store and read session data</h1>
                <p class="mt-2 text-sm text-sky-100">Try setting a value, then read it from the same browser session.</p>
            </div>

            <div class="space-y-6 px-6 py-6">
                @if (session('status'))
                    <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <form action="{{ route('session.demo') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_action" value="set">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-sky-500 dark:hover:bg-sky-400">
                            Write "hello" to session
                        </button>
                    </form>
                    <a href="{{ route('session.demo') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">
                        Refresh session value
                    </a>
                    <form action="{{ route('session.demo') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_action" value="delete">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-900/60">
                            Delete session value
                        </button>
                    </form>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-700 dark:bg-slate-900/60">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Current session value</p>
                    <div class="mt-2 flex items-center gap-3">
                        <span @class([
                            'inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide',
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => $hasValue,
                            'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' => ! $hasValue,
                        ])>
                            {{ $hasValue ? 'found' : 'empty' }}
                        </span>
                        <code class="rounded-md bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-800 dark:bg-slate-700 dark:text-slate-100">{{ $sessionValue ?? 'not set' }}</code>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
