<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ArchitectureControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_architecture_page_can_be_rendered(): void
    {
        $response = $this->get(route('architecture'));

        $response->assertOk();
        $response->assertViewIs('architecture');
        $response->assertSee('AWS Architecture Overview');
        $response->assertSee(URL::asset('images/aws-architecture.png'), false);
    }
}
