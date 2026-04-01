<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'plan' => $this->faker->randomElement(['start', 'standard', 'enterprise']),
            'billing_cycle' => $this->faker->randomElement(['monthly', 'yearly']),
            'status' => $this->faker->randomElement(['trial', 'active']),
            'price' => $this->faker->randomFloat(2, 0, 12000000),
            'currency' => 'UZS',
            'usage_limits' => ['max_doctors' => 100, 'max_branches' => 5],
            'usage_snapshot' => ['doctors' => 45, 'branches' => 2],
            'auto_renew' => true,
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->addMonth(),
            'trial_ends_at' => null,
            'created_by' => User::factory(),
        ];
    }
}

