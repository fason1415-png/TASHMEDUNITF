<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'name' => 'Branch '.$this->faker->citySuffix(),
            'code' => strtoupper($this->faker->bothify('BR-##??')),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'manager_name' => $this->faker->name(),
            'latitude' => $this->faker->latitude(37, 45),
            'longitude' => $this->faker->longitude(56, 74),
            'is_active' => true,
        ];
    }
}

