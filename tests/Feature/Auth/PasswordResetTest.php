<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status');
    }

    public function test_reset_password_link_request_requires_email(): void
    {
        $response = $this->from('/forgot-password')->post('/forgot-password', []);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('email');
    }

    public function test_reset_password_link_request_with_unknown_email_returns_error(): void
    {
        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'missing@example.com',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasInput('email', 'missing@example.com');
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $oldPasswordHash = $user->password;

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user, $oldPasswordHash) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            $user->refresh();

            $this->assertNotSame($oldPasswordHash, $user->password);
            $this->assertTrue(Hash::check('password', $user->password));
            $this->assertNotSame('', $user->remember_token);
            $this->assertSame(60, strlen($user->remember_token));

            return true;
        });
    }

    public function test_password_can_not_be_reset_with_invalid_token(): void
    {
        $user = User::factory()->create();
        $oldPasswordHash = $user->password;

        $response = $this->from('/reset-password/invalid-token')->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/reset-password/invalid-token');
        $response->assertSessionHasErrors('email');

        $this->assertSame($oldPasswordHash, $user->refresh()->password);
    }

    public function test_password_reset_requires_the_token(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/reset-password/token')->post('/reset-password', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/reset-password/token');
        $response->assertSessionHasErrors('token');
    }

    public function test_password_reset_requires_the_email(): void
    {
        $response = $this->from('/reset-password/token')->post('/reset-password', [
            'token' => 'token',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/reset-password/token');
        $response->assertSessionHasErrors('email');
    }

    public function test_password_reset_requires_the_password(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/reset-password/token')->post('/reset-password', [
            'token' => 'token',
            'email' => $user->email,
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/reset-password/token');
        $response->assertSessionHasErrors('password');
    }
}
