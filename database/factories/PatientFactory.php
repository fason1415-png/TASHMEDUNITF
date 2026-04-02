<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'pinfl' => $this->faker->unique()->numerify('##############'),
            'full_name' => $this->faker->name(),
            'birth_date' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->faker->phoneNumber(),
            'address_region' => $this->faker->randomElement(['Toshkent', 'Samarqand', 'Buxoro', 'Farg\'ona', 'Andijon', 'Namangan', 'Qashqadaryo', 'Surxondaryo', 'Xorazm', 'Navoiy', 'Jizzax', 'Sirdaryo', 'Qoraqalpog\'iston']),
            'address_district' => $this->faker->city(),
            'address_text' => $this->faker->address(),
            'territorial_clinic_id' => null,
            'family_doctor_id' => null,
            'is_active' => true,
            'meta' => null,
        ];
    }
}
