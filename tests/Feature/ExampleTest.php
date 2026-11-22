<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Http::fake([
            'www.equityworld-futures.com/api/portalnews*' => Http::response([
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
                'data' => [],
            ]),
            'www.equityworld-futures.com/api/berita' => Http::response([]),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
