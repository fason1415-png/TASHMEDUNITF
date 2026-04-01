<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted', 'login']),
            'auditable_type' => 'App\\Models\\Doctor',
            'auditable_id' => $this->faker->numberBetween(1, 999),
            'old_values' => null,
            'new_values' => ['status' => 'active'],
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'context' => ['channel' => 'admin'],
            'created_at' => now()->subMinutes($this->faker->numberBetween(1, 10000)),
        ];
    }
}

