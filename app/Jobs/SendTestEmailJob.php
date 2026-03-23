<?php

namespace App\Jobs;

use App\Mail\TestMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendTestEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('test@example.com')->send(new TestMail);

        // Simulate some processing time for the job, so we job executiorn is slow enough to be seen in visualisation.
        usleep(500_000);
    }
}
