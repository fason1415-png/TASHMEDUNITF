<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\QrCode;
use App\Models\QrScanEvent;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QrScanEvent>
 */
class QrScanEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'qr_code_id' => QrCode::factory(),
            'channel' => $this->faker->randomElement(['qr', 'shortlink', 'kiosk', 'telegram', 'sms']),
            'ip_hash' => hash('sha256', $this->faker->ipv4()),
            'device_hash' => hash('sha256', $this->faker->uuid()),
            'fingerprint_hash' => hash('sha256', $this->faker->sha1()),
            'user_agent' => $this->faker->userAgent(),
            'language' => $this->faker->randomElement(['uz_latn', 'uz_cyrl', 'ru', 'en']),
            'scanned_at' => now()->subMinutes($this->faker->numberBetween(1, 10000)),
            'converted_to_response_id' => null,
        ];
    }
}

