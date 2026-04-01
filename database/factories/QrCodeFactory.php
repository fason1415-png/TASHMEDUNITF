<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\QrCode;
use App\Models\ServicePoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QrCode>
 */
class QrCodeFactory extends Factory
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
            'target_type' => $this->faker->randomElement(['doctor', 'room', 'department', 'branch', 'service_type']),
            'target_id' => $this->faker->numberBetween(1, 1000),
            'code' => strtoupper(Str::random(10)),
            'token' => Str::random(40),
            'short_url' => $this->faker->url(),
            'is_active' => true,
            'meta' => ['print_label' => true],
            'scan_count' => $this->faker->numberBetween(0, 200),
            'printed_at' => now()->subDays($this->faker->numberBetween(0, 30)),
            'last_scanned_at' => now()->subHours($this->faker->numberBetween(0, 72)),
            'expires_at' => null,
            'created_by' => User::factory(),
        ];
    }
}

