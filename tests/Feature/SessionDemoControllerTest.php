<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionDemoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_session_page_shows_empty_state_when_no_value_set(): void
    {
        $this->actingAs(User::factory()->create());

        // Simulates user visiting the session demo page without any session value set.
        $response = $this->get(route('session.demo'));

        // Here we check what the user sees on the page.
        $response->assertOk();
        $response->assertSee('Current session value');
        $response->assertSee('not set');
        $response->assertSee('empty');
    }

    public function test_set_action_stores_value_and_redirects_back(): void
    {
        $this->actingAs(User::factory()->create());

        // User create a demo=hello session value.
        $response = $this->post(route('session.store'));

        // We check if the demo=hello exist in the session.
        $response->assertRedirect(route('session.demo'));
        $response->assertSessionHas('demo', 'hello');
        $response->assertSessionHas('status', 'Session value stored successfully.');

        /**
         * After the demo=hello creation, there will be a redirect, and our session value 'hello'
         * should be displayed to the user.
         */
        $this->followRedirects($response)
            ->assertOk()
            ->assertSee('hello')
            ->assertSee('found');
    }

    public function test_delete_action_removes_value_and_redirects_back(): void
    {
        $this->actingAs(User::factory()->create());

        // We start with a session value demo=hello, to test the deletion.
        $this->withSession(['demo' => 'hello']);

        $response = $this->delete(route('session.destroy'));

        $response->assertRedirect(route('session.demo'));
        $response->assertSessionMissing('demo');
        $response->assertSessionHas('status', 'Session value deleted successfully.');

        $this->followRedirects($response)
            ->assertOk()
            ->assertSee('not set')
            ->assertSee('empty');
    }
}
