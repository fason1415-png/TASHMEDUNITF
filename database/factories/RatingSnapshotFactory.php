<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\RatingSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RatingSnapshot>
 */
class RatingSnapshotFactory extends Factory
{
    public function definition(): array
    {
        $start = now()->startOfDay();

        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'doctor_id' => Doctor::factory(),
            'period_type' => 'daily',
            'period_start' => $start->toDateString(),
            'period_end' => $start->toDateString(),
            'feedback_count' => $this->faker->numberBetween(1, 150),
            'flagged_count' => $this->faker->numberBetween(0, 10),
            'quality_score' => $this->faker->randomFloat(2, 20, 100),
            'confidence_adjusted_score' => $this->faker->randomFloat(2, 20, 100),
            'sentiment_score' => $this->faker->randomFloat(2, -1, 1),
            'service_quality_score' => $this->faker->randomFloat(2, 20, 100),
            'communication_score' => $this->faker->randomFloat(2, 20, 100),
            'wait_time_score' => $this->faker->randomFloat(2, 20, 100),
            'explanation_score' => $this->faker->randomFloat(2, 20, 100),
            'resolution_score' => $this->faker->randomFloat(2, 20, 100),
            'nps_score' => $this->faker->randomFloat(2, 0, 100),
            'meta' => null,
        ];
    }
}

