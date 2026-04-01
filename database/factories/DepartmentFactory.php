<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'branch_id' => Branch::factory(),
            'name' => $this->faker->randomElement(['Cardiology', 'Neurology', 'Pediatrics', 'Surgery', 'Diagnostics']),
            'code' => strtoupper($this->faker->bothify('DP-##??')),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}

