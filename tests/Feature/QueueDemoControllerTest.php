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

    // public function test_dispatch_pushes_100_jobs_and_returns_json(): void
    // {
    //     $response = $this->postJson(route('queue.dispatch'));

    //     $response->assertOk();
    //     $response->assertJson([
    //         'status' => 'Dispatched 100 job(s)',
    //     ]);

    //     Queue::assertPushed(SendTestEmailJob::class, 100);
    // }

    public function test_index_hides_worker_command_instructions_outside_local(): void
    {
        $response = $this->get(route('queue.demo'));

        $response->assertDontSee('Run the worker');
        $response->assertDontSee('Retry all failed jobs');
        $response->assertDontSee('Flush the failed jobs table');
    }
}
