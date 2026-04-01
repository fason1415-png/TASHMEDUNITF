<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DoctorProfile>
 */
class DoctorProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'doctor_id' => Doctor::factory(),
            'education' => ['TMA', 'Residency'],
            'languages' => ['uz', 'ru', 'en'],
            'achievements' => ['Best clinician award'],
            'strengths' => ['communication', 'diagnostics'],
            'weaknesses' => ['queue management'],
            'ai_coaching_notes' => null,
            'monthly_target_score' => $this->faker->randomFloat(2, 70, 95),
        ];
    }
}

