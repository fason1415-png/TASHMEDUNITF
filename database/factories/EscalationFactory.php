<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Escalation;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Escalation>
 */
class EscalationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'survey_response_id' => SurveyResponse::factory(),
            'doctor_id' => Doctor::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'severity' => $this->faker->randomElement(['high', 'critical']),
            'category' => 'patient_feedback',
            'title' => 'Critical patient complaint',
            'description' => $this->faker->paragraph(),
            'source' => 'auto',
            'status' => 'open',
            'assigned_to' => User::factory(),
            'resolution_notes' => null,
            'opened_at' => now()->subHours(4),
            'resolved_at' => null,
            'sla_due_at' => now()->addHours(6),
            'meta' => null,
        ];
    }
}

