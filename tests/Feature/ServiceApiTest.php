<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_service_from_a_json_request(): void
    {
        $payload = [
            'title' => 'Consulting',
            'description' => 'Strategic advisory for product teams.',
            'icon' => 'briefcase',
        ];

        $response = $this->postJson('/api/services', $payload);

        $response
            ->assertCreated()
            ->assertJsonFragment($payload);

        $this->assertDatabaseHas('services', $payload);
    }
}