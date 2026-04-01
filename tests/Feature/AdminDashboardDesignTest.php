<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardDesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_modern_hero_widget(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create([
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super_admin');

        $response = $this->actingAs($user)->get('/admin?lang=en');

        $response
            ->assertOk()
            ->assertSee('Leadership Panel', escape: false)
            ->assertSee('Doctor Panel', escape: false)
            ->assertSee('LIVE ANALYTICS', escape: false);
    }
}
