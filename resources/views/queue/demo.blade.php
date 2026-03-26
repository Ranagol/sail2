<x-demo-layout>
    <div 
        x-data="pendingJobsComponent" 
        class="w-full space-y-6"
    >

        {{-- Header --}}
        @include('queue.partials.header')

            <div class="space-y-6 px-6 py-6">

                {{-- Live stats --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <!-- PENDING JOBS DISPLAY -->
                    <div
                        class="rounded-xl border border-violet-200 bg-violet-50 px-5 py-5 dark:border-violet-800 dark:bg-violet-900/20">

                        <p class="text-xs font-semibold uppercase tracking-wide text-violet-600 dark:text-violet-400">
                            Pending in Redis queue</p>
                        <p class="mt-2 text-4xl font-extrabold tabular-nums text-violet-700 dark:text-violet-300">
                            <span 
                                x-text="pendingJobsCount"
                            ></span>
                        </p>
                        <p class="mt-1 text-xs text-violet-600 dark:text-violet-400">Jobs waiting to be picked up by a worker.</p>
                    </div>

                    <!-- FAILED JOBS DISPLAY -->
                    @include('queue.partials.failed-jobs-display')
                </div>

                {{-- Dispatch form --}}
                <div
                    class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5 dark:border-slate-700 dark:bg-slate-900/60">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Dispatch jobs</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Dispatch 100 
                        <code class="rounded bg-slate-200 px-1 dark:bg-slate-700">SendTestEmailJob</code> 
                        jobs to thequeue with one click.
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Dispatches 100 jobs per click.</p>
                        <button 
                            type="submit" 
                            @click="dispatchJobs" 
                            :disabled="pendingJobsCount > 0"
                            class="inline-flex items-center gap-2
                                rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white transition
                                hover:bg-violet-500 disabled:cursor-not-allowed disabled:bg-slate-400
                                disabled:text-slate-100 dark:hover:bg-violet-400 dark:disabled:bg-slate-600
                                dark:disabled:text-slate-300"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>

                            Dispatch
                        </button>

                        <!-- DISPLAY: button will be disabled, until pending jobs > 0 -->
                        <template 
                            x-if="pendingJobsCount > 0"
                        >
                            <p class="text-sm font-medium text-amber-700 dark:text-amber-300">
                                Wait until the current queued jobs finish processing.
                            </p>
                        </template>
                    </div>

                </div>
        </section>

        {{-- How queues work --}}
        @include('queue.partials.how-queues-work')

        {{-- Recent failures --}}
        @include('queue.partials.recent-failures')

        @include('queue.partials.back-to-home')
    </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pendingJobsComponent', () => ({

                // Number of jobs pending in Redis
                pendingJobsCount: 0,

                // API route for polling
                url: '{{ route('queue.pending-jobs') }}',

                // Counter for polling duration
                secondsElapsed: 0,

                // Maximum polling duration in seconds
                maxSeconds: 60,

                // The current interval ID of the setInterval proces, that sends the repeated requests
                intervalId: null,

                /**
                 * Fetch the current number of pending jobs
                 */
                async getPendingJobsCount() {
                    try {
                        const response = await fetch(this.url);
                        const data = await response.json();
                        this.pendingJobsCount = data.pendingJobsCount;
                    } catch (error) {
                        console.error('Error fetching pending jobs count:', error);
                    }
                },

                /**
                 * Trigger dispatch of jobs on the backend
                 */
                async dispatchJobs() {
                    try {
                        await fetch('{{ route('queue.dispatch') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        });

                        // refresh state BEFORE starting sending repeated requests
                        await this.getPendingJobsCount();

                        this.startProcess(); // Start repeated request sending after click on dispatch button

                    } catch (error) {
                        console.error('Error dispatching jobs:', error);
                    }
                },

                /**
                 * Start polling pending jobs count every 1 second
                 * Stops either after maxSeconds or when pendingJobsCount reaches 0
                 */
                startProcess() {
                    this.secondsElapsed = 0; // reset counter

                    /**
                     * Clear any existing interval to avoid multiple intervals running simultaneously 
                     * if dispatch button is clicked multiple times
                     */
                    if (this.intervalId) {
                        clearInterval(this.intervalId);
                        this.intervalId = null;
                    }

                    // immediate first fetch so the user sees the count right away
                    this.getPendingJobsCount();

                    this.intervalId = setInterval(async () => {
                        await this.getPendingJobsCount();
                        this.secondsElapsed++;

                        if (this.secondsElapsed >= this.maxSeconds || this.pendingJobsCount === 0) {
                            clearInterval(this.intervalId); // stop sending repeated requests 
                            this.intervalId = null; // reset interval ID
                        }
                    }, 1000);
                },

                init() {
                    this.bootstrap();
                },
                

                /**
                 * Initialize component by fetching the initial pending jobs count
                 */
                async bootstrap() {
                    await this.getPendingJobsCount();

                    if (this.pendingJobsCount > 0) {

                        // Start sending repeated requests  if there are already pending jobs when component loads
                        this.startProcess(); 
                    }
                },

            }));
        });




    </script>
</x-demo-layout>
