<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Survey>
 */
class SurveyFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Default Patient Survey';

        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'name' => $name,
            'slug' => 'default-'.$this->faker->unique()->numberBetween(10, 99),
            'title' => [
                'uz_latn' => 'Shifokor xizmati bo`yicha fikr',
                'uz_cyrl' => 'Шифокор хизмати бўйича фикр',
                'ru' => 'Оцените визит к врачу',
                'en' => 'Rate your doctor visit',
            ],
            'description' => [
                'uz_latn' => '45 soniyada anonim so`rovnoma',
                'uz_cyrl' => '45 сонияда аноним сўровнома',
                'ru' => 'Анонимный опрос за 45 секунд',
                'en' => 'Anonymous survey in under 45 seconds',
            ],
            'is_default' => true,
            'is_active' => true,
            'allow_anonymous' => true,
            'require_token_verification' => false,
            'callback_enabled' => true,
            'estimated_seconds' => 45,
            'config' => ['show_progress' => false],
            'starts_at' => now()->subDay(),
            'ends_at' => null,
        ];
    }
}

