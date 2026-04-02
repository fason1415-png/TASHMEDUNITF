<?php

namespace Database\Factories;

use App\Models\PatronageNotification;
use App\Models\PatronageTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PatronageNotification>
 */
class PatronageNotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patronage_task_id' => PatronageTask::factory(),
            'channel' => $this->faker->randomElement(['sms', 'push', 'telegram', 'email']),
            'recipient_type' => $this->faker->randomElement(['doctor', 'supervisor', 'chief_doctor', 'ministry']),
            'recipient_id' => null,
            'message_body' => $this->faker->paragraph(),
            'status' => 'pending',
            'sent_at' => null,
            'delivered_at' => null,
            'attempt_count' => 0,
            'error_message' => null,
            'meta' => null,
        ];
    }
}
