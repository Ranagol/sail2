<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verified_at_is_cast_to_carbon_datetime(): void
    {
        $user = User::factory()->create();
        $user->refresh();

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
    }

    public function test_email_verified_at_cast_is_null_when_not_set(): void
    {
        $user = User::factory()->unverified()->create();

        $this->assertNull($user->email_verified_at);
    }
}
