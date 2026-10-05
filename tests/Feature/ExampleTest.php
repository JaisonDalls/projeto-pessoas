<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_assets_use_https_behind_a_tls_terminating_proxy(): void
    {
        $response = $this
            ->withHeader('X-Forwarded-Proto', 'https')
            ->get('/');

        $response
            ->assertOk()
            ->assertSee('https://localhost:8000/build/', escape: false)
            ->assertDontSee('http://localhost:8000/build/', escape: false);
    }
}
