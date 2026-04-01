<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\RatingSnapshot;
use App\Models\Reward;
use App\Models\RewardRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Reward>
 */
class RewardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'reward_rule_id' => RewardRule::factory(),
            'rating_snapshot_id' => RatingSnapshot::factory(),
            'doctor_id' => Doctor::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'title' => 'Top Doctor Reward',
            'description' => $this->faker->sentence(),
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'eligibility_score' => $this->faker->randomFloat(2, 50, 100),
            'status' => $this->faker->randomElement(['eligible', 'approved', 'paid']),
            'approved_by' => User::factory(),
            'approved_at' => now()->subDays(5),
            'paid_at' => null,
            'amount' => 500000,
            'currency' => 'UZS',
            'notes' => null,
            'meta' => null,
        ];
    }
}

