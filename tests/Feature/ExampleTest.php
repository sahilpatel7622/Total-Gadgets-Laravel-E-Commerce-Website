<?php

namespace Tests\Feature;

use App\Http\Middleware\MaintenanceMode;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_redirects_to_dashboard(): void
    {
        $response = $this
            ->withoutMiddleware(MaintenanceMode::class)
            ->get('/');

        $response->assertRedirect(route('dashboard'));
    }
}