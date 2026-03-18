<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestEmailJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\View\View;

class QueueDemoController extends Controller
{
    public function index(): View
    {
        return view('queue.demo', [
            'pendingJobsCount' => Queue::size(),
            'failedJobsCount' => DB::table('failed_jobs')->count(),
            'recentFailedJobs' => DB::table('failed_jobs')
                ->orderByDesc('failed_at')
                ->limit(5)
                ->get(['id', 'queue', 'failed_at', 'exception']),
        ]);
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $count = (int) $request->input('count', 10);
        $count = max(1, min(100, $count));

        for ($i = 0; $i < $count; $i++) {
            SendTestEmailJob::dispatch();
        }

        return redirect()
            ->route('queue.demo')
            ->with('status', "Dispatched {$count} job(s) to the Redis queue.");
    }
}
