<?php

namespace Tests\Feature;

use App\Models\Plugin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PluginManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_plugins_page_shows_plugins_from_database(): void
    {
        Plugin::create([
            'title' => 'Studio Bundle',
            'price' => 250000,
            'description' => 'Plugin premium untuk mixing.',
        ]);

        $response = $this->get('/shop/plugins-vst');

        $response->assertOk();
        $response->assertSee('Studio Bundle');
        $response->assertSee('Rp 250.000');
    }
}
