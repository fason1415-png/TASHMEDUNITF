<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\QrCode;
use App\Models\ServicePoint;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SurveyResponse>
 */
class SurveyResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'doctor_id' => Doctor::factory(),
            'service_point_id' => ServicePoint::factory(),
            'qr_code_id' => QrCode::factory(),
            'survey_id' => Survey::factory(),
            'channel' => $this->faker->randomElement(['qr', 'shortlink', 'kiosk', 'telegram', 'sms']),
            'submitted_at' => now()->subMinutes($this->faker->numberBetween(1, 10000)),
            'language' => $this->faker->randomElement(['uz_latn', 'uz_cyrl', 'ru', 'en']),
            'ip_hash' => hash('sha256', $this->faker->ipv4()),
            'device_hash' => hash('sha256', $this->faker->uuid()),
            'fingerprint_hash' => hash('sha256', $this->faker->sha1()),
            'verified_token' => null,
            'fraud_score' => $this->faker->randomFloat(2, 0, 80),
            'anomaly_score' => $this->faker->randomFloat(2, 0, 50),
            'sentiment_score' => $this->faker->randomFloat(2, -1, 1),
            'severity_score' => $this->faker->randomFloat(2, 1, 5),
            'confidence_score' => $this->faker->randomFloat(2, 20, 100),
            'quality_score' => $this->faker->randomFloat(2, 20, 100),
            'is_flagged' => false,
            'moderation_status' => $this->faker->randomElement(['pending', 'approved', 'needs_review']),
            'is_duplicate' => false,
            'duplicate_of_response_id' => null,
            'callback_requested' => false,
            'callback_contact' => null,
            'callback_note' => null,
            'submitted_from_country' => 'UZ',
            'ai_processed_at' => null,
        ];
    }
}

