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

    public function test_session_page_shows_empty_state_when_no_value_set(): void
    {
        $response = $this->get(route('session.demo'));

        $response->assertOk();
        $response->assertSee('Current session value');
        $response->assertSee('not set');
        $response->assertSee('empty');
    }

    public function test_set_action_stores_value_and_redirects_back(): void
    {
        $response = $this->post(route('session.demo'), ['_action' => 'set']);

        $response->assertRedirect(route('session.demo'));
        $response->assertSessionHas('demo', 'hello');
        $response->assertSessionHas('status', 'Session value stored successfully.');

        $this->followRedirects($response)
            ->assertOk()
            ->assertSee('hello')
            ->assertSee('found');
    }

    public function test_delete_action_removes_value_and_redirects_back(): void
    {
        $this->withSession(['demo' => 'hello']);

        $response = $this->post(route('session.demo'), ['_action' => 'delete']);

        $response->assertRedirect(route('session.demo'));
        $response->assertSessionMissing('demo');
        $response->assertSessionHas('status', 'Session value deleted successfully.');

        $this->followRedirects($response)
            ->assertOk()
            ->assertSee('not set')
            ->assertSee('empty');
    }
}
