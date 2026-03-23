<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestEmailJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\View\View;

/**
 * The idea is, that the user sends 100 emails, as jobs to the Redis queue.
 * These jobs will be stored in the Redis queue, what we want to demonstrate here. Once the worker
 * is activated, these jobs will be done, and the 100 mails will be sent. We can see the pending
 * jobs in the Redis queue. Now, as soon as the user clicks on the 'Dispatch' button, the blade page
 * will be refreshed every 1 second for a maximum of 60 seconds or until there are pending jobs.
 * This way the user can see the pending jobs count decreasing in real time.
 */
class QueueDemoController extends Controller
{
    /**
     * The start time will be stored in session. This is the key for it in the session.
     */
    private string $startTime = 'start_time';

    /**
     * The page refresh can last max 60 seconds. Not more.
     */
    private int $timeLimit = 60;

    /**
     * This method renders the queue demo page and optionally tells the browser to refresh it.
     */
    public function index(): View|Response
    {
        // Count how many jobs are still waiting in the Redis queue.
        $pendingJobsCount = Queue::size();

        // Count how many jobs have failed and were written into the failed_jobs database table.
        $failedJobsCount = DB::table('failed_jobs')->count();

        /**
         * Build the Blade view that will display the queue statistics and recent failed jobs.
         * This view is always returned, no matter if we refresh the page or not.
         */
        $view = view('queue.demo', [
            'pendingJobsCount' => $pendingJobsCount,
            'failedJobsCount' => $failedJobsCount,
            'recentFailedJobs' => DB::table('failed_jobs')
                ->orderByDesc('failed_at')
                ->limit(5)
                ->get(['id', 'queue', 'failed_at', 'exception']),
        ]);

        /**
         * Read from the session when the auto-refresh period started, or use 0 if it was never set.
         * This value was set by the $this->dispatch() method.
         */
        $startTime = (int) session($this->startTime, 0);

        // Check whether we actually have a valid stored start time that is greater than 0.
        $isStartTimeExist = $startTime > 0;

        // Calculate how many seconds have passed since start time.
        $secondsSinceStart = now()->timestamp - $startTime;

        /**
         * Determine whether the 60-second refresh window is still active. It is, if:
         * 1. $startTime still exists in the session
         * 2. Less than 60 seconds have passed since the start time
         */
        $is60SecNotExpired = $isStartTimeExist && $secondsSinceStart < $this->timeLimit;

        // Refresh only if there are still pending jobs and the refresh window has not expired.
        $shouldRefresh = $pendingJobsCount > 0 && $is60SecNotExpired;

        // If the page should no longer refresh, clean up the session key and return the normal view.
        if (! $shouldRefresh) {

            // Remove the stored refresh start time from the session because it is no longer needed.
            session()->forget($this->startTime);

            // Return the normal page view response without any refresh.
            return $view;
        }

        /**
         * But, if the page must be refreshed...
         * We return a Response (not a View) with the rendered Blade view as content.
         * We also add an HTTP Refresh header so the browser reloads it after 1 second.
         * 1 - means refreshing every 1 second
         * 2 - queue.demo is the 'queue-testing' url
         */
        return response($view)->header('Refresh', '1;url='.route('queue.demo'));
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $count = (int) $request->input('count', 100);

        // Starts the job dispatching (mail sending)
        for ($i = 0; $i < $count; $i++) {
            SendTestEmailJob::dispatch();
        }

        /**
         * We write into the session, when we started the dispatching of jobs. From this moment,
         * the Blade page may refresh every second for a maximum of 60 seconds, so the user can see
         * the progress of job dispatches.
         */
        session()->put($this->startTime, now()->timestamp);

        /**
         * Here we simply refresh the page, so we can display the status message to the user.
         */
        return redirect()
            ->route('queue.demo')
            ->with('status', "Dispatched {$count} job(s) to the Redis queue.");
    }
}
