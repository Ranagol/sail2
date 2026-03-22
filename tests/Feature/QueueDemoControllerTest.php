<?php

namespace Tests\Feature;

use App\Jobs\SendTestEmailJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueDemoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        // This user is in DB, its ID is put into the test session/auth guard state so Laravel treats requests as authenticated
        $this->actingAs(User::factory()->create());

        // We fake the queue, so that when the controller dispatches jobs, they are not actually executed.
        // This allows us to assert that jobs were pushed onto the queue without worrying about their side effects.
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
        /**
         * Simulates a user submitting a form. Count is the number of jobs, 100. So, user send in the
         * form the number 100. He wants to dispatch 100 jobs to the queue.
         */
        $response = $this->post(route('queue.dispatch'), ['count' => 100]);

        // We assert that the user is redirected back to the queue demo page
        $response->assertRedirect(route('queue.demo'));

        // We assert that a session flash message is set with the expected status message.
        $response->assertSessionHas('status', 'Dispatched 100 job(s) to the Redis queue.');

        // We assert that exactly 5 SendTestEmailJob jobs were pushed onto the queue.
        Queue::assertPushed(SendTestEmailJob::class, 100);
    }

    public function test_index_shows_worker_command_instructions(): void
    {
        $response = $this->get(route('queue.demo'));

        $response->assertSee('queue:work');
        $response->assertSee('queue:retry all');
        $response->assertSee('queue:flush');
    }
}
