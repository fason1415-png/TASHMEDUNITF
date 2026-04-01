<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Clinic>
 */
class ClinicFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Shifo '.$this->faker->unique()->company();

        return [
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'legal_name' => $name.' MCHJ',
            'region' => $this->faker->state(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'branding' => [
                'primary_color' => '#0f766e',
                'secondary_color' => '#0f172a',
            ],
            'scoring_weights' => config('shiforeyting.default_scoring_weights'),
            'ai_settings' => ['auto_summary' => true, 'toxicity_threshold' => 0.7],
            'min_public_samples' => 10,
            'subscription_plan' => $this->faker->randomElement(['start', 'standard', 'enterprise']),
            'trial_ends_at' => now()->addDays(14),
            'is_active' => true,
        ];
    }
}
