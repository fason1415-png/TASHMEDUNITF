<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalePersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_locale_persists_across_pages_and_translates_navigation(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create([
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super_admin');

        $this->actingAs($user)
            ->get('/admin?lang=ru')
            ->assertOk()
            ->assertSee(__('resources.branch.navigation', [], 'ru'), escape: false)
            ->assertSee(__('resources.clinic.navigation', [], 'ru'), escape: false);

        $this->actingAs($user)
            ->get('/admin/export-center')
            ->assertOk()
            ->assertSee(__('pages.export_center.title', [], 'ru'), escape: false);

        $this->actingAs($user)
            ->get('/admin/branches')
            ->assertOk()
            ->assertSee(__('resources.branch.navigation', [], 'ru'), escape: false)
            ->assertSee(__('resources.clinic.navigation', [], 'ru'), escape: false);

        $this->actingAs($user)
            ->get('/admin?lang=en')
            ->assertOk()
            ->assertSee(__('ui.executive.title', [], 'en'), escape: false);

        $this->actingAs($user)
            ->get('/admin/export-center')
            ->assertOk()
            ->assertSee(__('pages.export_center.title', [], 'en'), escape: false);

        $this->actingAs($user)
            ->get('/admin?lang=uz_latn')
            ->assertOk()
            ->assertSee(__('ui.executive.title', [], 'uz_latn'), escape: false);
    }
}

