<?php

namespace Tests\Feature;

use App\Jobs\SendTestEmailJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class QueueDemoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Queue::fake();
    }

    public function test_index_page_loads_successfully(): void
    {
        $response = $this->get(route('queue.demo'));

        $response->assertOk();
        $response->assertSee('Queue Demo');
        $response->assertSee('Pending in Redis queue');
        $response->assertSee('Failed jobs');
        $response->assertSee('How it works');
    }

    public function test_dispatch_pushes_jobs_onto_queue_and_redirects(): void
    {
        $response = $this->post(route('queue.dispatch'), ['count' => 5]);

        $response->assertRedirect(route('queue.demo'));
        $response->assertSessionHas('status', 'Dispatched 5 job(s) to the Redis queue.');
        Queue::assertPushed(SendTestEmailJob::class, 5);
    }

    public function test_dispatch_defaults_to_ten_jobs(): void
    {
        $response = $this->post(route('queue.dispatch'));

        Queue::assertPushed(SendTestEmailJob::class, 10);
        $response->assertSessionHas('status', 'Dispatched 10 job(s) to the Redis queue.');
    }

    public function test_dispatch_clamps_count_to_maximum_of_100(): void
    {
        $this->post(route('queue.dispatch'), ['count' => 999]);

        Queue::assertPushed(SendTestEmailJob::class, 100);
    }

    public function test_dispatch_clamps_count_to_minimum_of_1(): void
    {
        $this->post(route('queue.dispatch'), ['count' => 0]);

        Queue::assertPushed(SendTestEmailJob::class, 1);
    }

    public function test_dispatch_count_is_cast_to_integer(): void
    {
        $response = $this->post(route('queue.dispatch'), ['count' => '2.9']);

        Queue::assertPushed(SendTestEmailJob::class, 2);
        $response->assertSessionHas('status', 'Dispatched 2 job(s) to the Redis queue.');
    }

    public function test_index_shows_worker_command_instructions(): void
    {
        $response = $this->get(route('queue.demo'));

        $response->assertSee('queue:work');
        $response->assertSee('queue:retry all');
        $response->assertSee('queue:flush');
    }

    public function test_index_shows_correct_failed_jobs_count(): void
    {
        $this->insertFailedJobs(3);

        $response = $this->get(route('queue.demo'));

        $response->assertViewHas('failedJobsCount', 3);
    }

    public function test_index_shows_at_most_five_recent_failed_jobs(): void
    {
        $this->insertFailedJobs(7);

        $response = $this->get(route('queue.demo'));

        $response->assertViewHas('recentFailedJobs', fn ($jobs) => $jobs->count() === 5);
    }

    public function test_index_returns_correct_columns_for_failed_jobs(): void
    {
        $this->insertFailedJobs(1);

        $response = $this->get(route('queue.demo'));

        $response->assertViewHas('recentFailedJobs', function ($jobs) {
            $job = $jobs->first();

            return isset($job->id, $job->queue, $job->failed_at, $job->exception);
        });
    }

    private function insertFailedJobs(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            DB::table('failed_jobs')->insert([
                'uuid' => Str::uuid()->toString(),
                'connection' => 'redis',
                'queue' => 'default',
                'payload' => '{}',
                'exception' => "Exception {$i}",
                'failed_at' => now()->subSeconds($i)->toDateTimeString(),
            ]);
        }
    }
}
