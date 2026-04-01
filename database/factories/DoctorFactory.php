<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'full_name' => $this->faker->name('male'),
            'specialty' => $this->faker->randomElement(['Therapist', 'Cardiologist', 'Neurologist', 'Pediatrician']),
            'status' => 'active',
            'photo' => null,
            'experience_years' => $this->faker->numberBetween(1, 25),
            'bio' => $this->faker->paragraph(),
            'consultation_type' => $this->faker->randomElement(['offline', 'online', 'hybrid']),
            'is_active' => true,
            'hired_at' => $this->faker->dateTimeBetween('-10 years', '-1 month'),
            'left_at' => null,
        ];
    }
}

