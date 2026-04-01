<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\RewardRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RewardRule>
 */
class RewardRuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'name' => 'Monthly Top Performer',
            'description' => 'Reward for top confidence-adjusted score.',
            'trigger_type' => 'rank',
            'conditions' => ['rank' => 1, 'minimum_feedback' => 30],
            'reward_type' => 'bonus',
            'reward_value' => 500000,
            'reward_meta' => ['currency' => 'UZS'],
            'period_type' => 'monthly',
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}

