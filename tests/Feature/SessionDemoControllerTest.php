<?php

namespace Tests\Feature;

use Tests\TestCase;

class SessionDemoControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_session_get_page_shows_default_value_when_missing(): void
    {
        $response = $this->get(route('session.get'));

        $response->assertOk();
        $response->assertSee('Current session value');
        $response->assertSee('not found');
        $response->assertSee('empty');
    }

    public function test_session_set_route_stores_value_and_redirects_to_session_get(): void
    {
        $response = $this->get(route('session.set'));

        $response->assertRedirect(route('session.get'));
        $response->assertSessionHas('demo', 'hello');
        $response->assertSessionHas('status', 'Session value stored successfully.');

        $this->followRedirects($response)
            ->assertOk()
            ->assertSee('hello')
            ->assertSee('found');
    }

    public function test_session_delete_route_removes_value_and_redirects_to_session_get(): void
    {
        $this->withSession(['demo' => 'hello']);

        $response = $this->post(route('session.delete'));

        $response->assertRedirect(route('session.get'));
        $response->assertSessionMissing('demo');
        $response->assertSessionHas('status', 'Session value deleted successfully.');

        $this->followRedirects($response)
            ->assertOk()
            ->assertSee('not found')
            ->assertSee('empty');
    }
}
