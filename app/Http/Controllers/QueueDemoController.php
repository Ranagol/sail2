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
 * jobs in the Redis queue.
 */
class QueueDemoController extends Controller
{
    public function index(Request $request): View|Response
    {
        $pendingJobsCount = Queue::size();
        $failedJobsCount = DB::table('failed_jobs')->count();

        $view = view('queue.demo', [
            'pendingJobsCount' => $pendingJobsCount,
            'failedJobsCount' => $failedJobsCount,
            'recentFailedJobs' => DB::table('failed_jobs')
                ->orderByDesc('failed_at')
                ->limit(5)
                ->get(['id', 'queue', 'failed_at', 'exception']),
        ]);

        $watchStartedAt = (int) $request->query('started', 0);
        $watchDurationInSeconds = 30;
        $watchNotExpired = $watchStartedAt > 0 && (now()->timestamp - $watchStartedAt) < $watchDurationInSeconds;
        $shouldRefresh = $request->boolean('watch') && $pendingJobsCount > 0 && $watchNotExpired;

        if (! $shouldRefresh) {
            return $view;
        }

        $refreshUrl = route('queue.demo', [
            'watch' => 1,
            'started' => $watchStartedAt,
        ]);

        return response($view)->header('Refresh', "1;url={$refreshUrl}");
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $count = (int) $request->input('count', 100);

        for ($i = 0; $i < $count; $i++) {
            SendTestEmailJob::dispatch();
        }

        return redirect()
            ->route('queue.demo', [
                'watch' => 1,
                'started' => now()->timestamp,
            ])
            ->with('status', "Dispatched {$count} job(s) to the Redis queue.");
    }
}
