<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\SuspiciousFlag;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SuspiciousFlag>
 */
class SuspiciousFlagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'survey_response_id' => SurveyResponse::factory(),
            'flag_type' => $this->faker->randomElement(['ip_duplicate', 'device_duplicate', 'time_burst', 'pattern', 'toxicity', 'ai_anomaly']),
            'score' => $this->faker->randomFloat(2, 10, 100),
            'reason' => $this->faker->sentence(),
            'evidence' => ['source' => 'factory'],
            'status' => $this->faker->randomElement(['open', 'confirmed', 'dismissed']),
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }
}
