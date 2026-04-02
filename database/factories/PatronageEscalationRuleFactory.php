<?php

namespace Database\Factories;

use App\Models\PatronageEscalationRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PatronageEscalationRule>
 */
class PatronageEscalationRuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => null,
            'escalation_level' => $this->faker->numberBetween(1, 3),
            'trigger_after_minutes' => $this->faker->randomElement([1440, 2880, 4320]),
            'notify_role' => $this->faker->randomElement(['supervisor', 'chief_doctor', 'ministry_rep']),
            'notification_channels' => ['sms', 'telegram'],
            'auto_reassign' => false,
            'is_active' => true,
        ];
    }
}
