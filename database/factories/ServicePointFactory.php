<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\ServicePoint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ServicePoint>
 */
class ServicePointFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'name' => 'Room '.$this->faker->numberBetween(100, 399),
            'type' => $this->faker->randomElement(['room', 'service_type', 'kiosk']),
            'code' => strtoupper($this->faker->bothify('SP-##??')),
            'location_hint' => $this->faker->randomElement(['1st floor', '2nd floor', 'Main hall']),
            'is_active' => true,
        ];
    }
}

