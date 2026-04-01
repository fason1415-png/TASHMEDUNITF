<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_root_renders_modern_landing_page(): void
    {
        $response = $this->get('/?lang=uz_latn');

        $response
            ->assertOk()
            ->assertSee('Rahbariyat uchun dashboard', escape: false);
    }

    public function test_root_supports_ru_and_en_variants(): void
    {
        $ruLabel = __('ui.landing.management_panel', [], 'ru');
        $enLabel = __('ui.landing.management_panel', [], 'en');

        $this->get('/?lang=ru')
            ->assertOk()
            ->assertSee($ruLabel, escape: false);

        $this->get('/?lang=en')
            ->assertOk()
            ->assertSee($enLabel, escape: false);
    }
}
