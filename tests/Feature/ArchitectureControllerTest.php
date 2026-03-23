<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ArchitectureControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_when_visiting_architecture_page(): void
    {
        $response = $this->get(route('architecture'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_render_architecture_page(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('architecture'));

        $response->assertOk();
        $response->assertViewIs('architecture');
        $response->assertSee('AWS Architecture Overview');
        $response->assertSee(URL::asset('images/aws-architecture.png'), false);
    }
}
