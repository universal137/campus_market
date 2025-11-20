<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_render_seeded_content(): void
    {
        $this->seed();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('校园易');
    }
}
