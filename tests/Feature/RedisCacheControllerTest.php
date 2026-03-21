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
        User::factory()->count(3)->create();

        $response = $this->get(route('redis.demo'));

        $response->assertOk();
        $response->assertSee('Redis Cache Demo');
        $response->assertSee('1st request');
        $response->assertSee('2nd request');
    }

    public function test_redis_demo_page_shows_user_count(): void
    {
        User::factory()->count(5)->create();

        $response = $this->get(route('redis.demo'));

        $response->assertOk();
        $response->assertSee('5 users');
    }

    public function test_redis_demo_page_shows_timing_values(): void
    {
        User::factory()->count(3)->create();

        $response = $this->get(route('redis.demo'));

        $response->assertOk();
        $response->assertSee('ms');
        $response->assertSee('How it works');
        $response->assertSee('Run benchmark again');
    }

    public function test_redis_demo_clears_cache_before_first_request(): void
    {
        User::factory()->count(3)->create();
        Cache::put('users.all', collect(['stale']), 60);

        $this->get(route('redis.demo'));

        $this->assertTrue(Cache::has('users.all'));
        $this->assertNotEquals(collect(['stale']), Cache::get('users.all'));
    }

    public function test_redis_demo_passes_valid_numeric_view_data(): void
    {
        User::factory()->count(3)->create();

        $response = $this->get(route('redis.demo'));

        $response->assertViewHas('usersCount', 3);
        $response->assertViewHas('firstDurationMs', fn ($v) => $v > 0 && $v < 30000);
        $response->assertViewHas('secondDurationMs', fn ($v) => $v > 0 && $v < 30000);
        $response->assertViewHas('speedupFactor', fn ($v) => $v !== null && $v > 0);
    }

    public function test_users_are_cached_with_sixty_second_ttl(): void
    {
        User::factory()->count(3)->create();

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
