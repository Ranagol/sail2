<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RedisCacheControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_redis_demo_page_loads_successfully(): void
    {
        // We create 25 users in the test db, because we measure the time it takes to retrieve all users.
        User::factory()->count(25)->create();
        $this->actingAs(User::query()->firstOrFail());

        $response = $this->get(route('redis.demo'));

        $response->assertOk();
        $response->assertSee('Redis Cache Demo');
        $response->assertSee('1st request');
        $response->assertSee('2nd request');
    }

    public function test_redis_demo_page_shows_user_count(): void
    {
        User::factory()->count(25)->create();
        $this->actingAs(User::query()->firstOrFail());

        $response = $this->get(route('redis.demo'));

        $response->assertOk();
        $response->assertSee('25 users');
    }

    public function test_redis_demo_page_shows_timing_values(): void
    {
        User::factory()->count(3)->create();
        $this->actingAs(User::query()->firstOrFail());

        $response = $this->get(route('redis.demo'));

        $response->assertOk();
        $response->assertSee('ms');
        $response->assertSee('How it works');
        $response->assertSee('Run benchmark again');
    }

    public function test_redis_demo_clears_cache_before_first_request(): void
    {
        // Create new fresh users
        User::factory()->count(3)->create();
        $this->actingAs(User::query()->firstOrFail());

        /**
         * Creates a collection, that contains 'stale' string. This symbolises stale data. We insert
         * this stale data into cache. This should be deleted by the controller before the first
         * request. We have now in Cache: users.all => ['stale']
         */
        Cache::put('users.all', collect(['stale']), 60);

        // User 'visits' the page, which should trigger the cache clearing and data retrieval.
        $this->get(route('redis.demo'));

        // Check if the Cache has the 'users.all'
        $this->assertTrue(Cache::has('users.all'));

        // Check that Cache does not have this: users.all => ['stale']
        $this->assertNotEquals(collect(['stale']), Cache::get('users.all'));

        // Check that Cache has the correct data: users.all => collection of 3 users.
        $this->assertEquals(3, Cache::get('users.all')->count());
    }

    public function test_redis_demo_passes_valid_numeric_view_data(): void
    {
        // We need users in db, so we can measure how fast we can get them.
        User::factory()->count(3)->create();

        // The user must be authenticated, because the controller has auth middleware.
        $this->actingAs(User::query()->firstOrFail());

        // We 'visit' the page, and we check that the view has the correct data types and values.
        $response = $this->get(route('redis.demo'));

        $response->assertViewHas('usersCount', 3);

        // Value is positive and less than 30 seconds (30000 ms),
        $response->assertViewHas('firstDurationMs', fn ($v) => $v > 0 && $v < 30000);

        // Value is positive and less than 30 seconds (30000 ms),
        $response->assertViewHas('secondDurationMs', fn ($v) => $v > 0 && $v < 30000);

        // Check if the firstDurationMs is greater than the secondDurationMs. (it should be)
        $firstDurationMs = $response->viewData('firstDurationMs');
        $secondDurationMs = $response->viewData('secondDurationMs');
        $this->assertGreaterThan($secondDurationMs, $firstDurationMs);

        // It is not null, and it is a positive number (it should be).
        $response->assertViewHas('speedupFactor', fn ($v) => $v !== null && $v > 0);
    }

    public function test_users_are_cached_with_sixty_second_ttl(): void
    {
        User::factory()->count(3)->create();
        $this->actingAs(User::query()->firstOrFail());

        $writtenEvents = [];
        $this->app->make('events')->listen(
            KeyWritten::class,
            function ($event) use (&$writtenEvents): void {
                if ($event->key === 'users.all') {
                    $writtenEvents[] = $event;
                }
            }
        );

        $this->get(route('redis.demo'))->assertOk();

        $this->assertCount(1, $writtenEvents);
        $this->assertEquals(60, $writtenEvents[0]->seconds);
    }
}
