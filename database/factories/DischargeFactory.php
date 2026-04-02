<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Discharge;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discharge>
 */
class DischargeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'patient_id' => Patient::factory(),
            'branch_id' => null,
            'department_id' => null,
            'attending_doctor_id' => null,
            'diagnosis_code' => $this->faker->randomElement(['J18.9', 'I10', 'K29.7', 'E11.9', 'J06.9', 'M54.5', 'N39.0']),
            'diagnosis_text' => $this->faker->sentence(),
            'severity_level' => $this->faker->randomElement(['mild', 'moderate', 'severe', 'critical']),
            'discharge_type' => $this->faker->randomElement(['recovery', 'improvement', 'transfer', 'against_advice']),
            'requires_patronage' => $this->faker->boolean(60),
            'recommended_visit_days' => [1, 3, 7, 14],
            'discharged_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'created_by' => null,
        ];
    }
}
