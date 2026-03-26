<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestEmailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\View\View;

/**
 * The idea is, that the user sends 100 emails, as jobs to the Redis queue.
 * These jobs will be stored in the Redis queue, what we want to demonstrate here. Once the worker
 * is activated, these jobs will be done, and the 100 mails will be sent. We can see the pending
 * jobs in the Redis queue.
 */
class QueueDemoController extends Controller
{
    private int $numberOfEmailsToSend = 100;

    public function index(): View
    {
        // Count how many jobs are still waiting in the Redis queue.
        $pendingJobsCount = Queue::size();

        // Count how many jobs have failed and were written into the failed_jobs database table.
        $failedJobsCount = DB::table('failed_jobs')->count();

        // Build the Blade view that will display the queue statistics and recent failed jobs.
        return view('queue.demo', [
            'pendingJobsCount' => $pendingJobsCount,
            'failedJobsCount' => $failedJobsCount,
            'recentFailedJobs' => DB::table('failed_jobs')
                ->orderByDesc('failed_at')
                ->limit(5)
                ->get(['id', 'queue', 'failed_at', 'exception']),
        ]);
    }

    /**
     * Dispatches the jobs on request from the FE Alpine.js
     */
    public function dispatch(Request $request): JsonResponse
    {

        // Starts the job dispatching (mail sending)
        for ($i = 0; $i < $this->numberOfEmailsToSend; $i++) {
            SendTestEmailJob::dispatch();
        }

        return response()->json([
            'status' => "Dispatched {$this->numberOfEmailsToSend} job(s)",
        ]);
    }

    /**
     * Return the current number of pending jobs as JSON (for Alpine.js polling)
     */
    public function pendingJobsCount(): JsonResponse
    {
        $pendingJobsCount = Queue::size();

        return response()->json(['pendingJobsCount' => $pendingJobsCount]);
    }
}
