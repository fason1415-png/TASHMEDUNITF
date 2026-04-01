<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\LanguageString;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LanguageString>
 */
class LanguageStringFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'namespace' => 'app',
            'group' => 'survey',
            'key' => 'welcome_text_'.$this->faker->unique()->numerify('###'),
            'locale' => $this->faker->randomElement(['uz_latn', 'uz_cyrl', 'ru', 'en']),
            'value' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}

