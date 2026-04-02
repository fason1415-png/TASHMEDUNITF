<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Discharge;
use App\Models\Patient;
use App\Models\PatronageTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PatronageTask>
 */
class PatronageTaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'hospital_clinic_id' => Clinic::factory(),
            'discharge_id' => Discharge::factory(),
            'patient_id' => Patient::factory(),
            'family_doctor_id' => null,
            'task_type' => $this->faker->randomElement(['initial_visit', 'follow_up', 'emergency_check']),
            'priority' => $this->faker->randomElement(['normal', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['pending', 'notified', 'accepted', 'in_progress', 'completed', 'missed', 'escalated']),
            'due_at' => $this->faker->dateTimeBetween('now', '+14 days'),
            'notified_at' => null,
            'accepted_at' => null,
            'visited_at' => null,
            'completed_at' => null,
            'visit_notes' => null,
            'visit_outcome' => null,
            'patient_condition_score' => null,
            'gps_latitude' => null,
            'gps_longitude' => null,
            'photo_proof_path' => null,
            'escalation_level' => 0,
            'sla_breached' => false,
            'sla_breach_minutes' => 0,
            'meta' => null,
        ];
    }
}
