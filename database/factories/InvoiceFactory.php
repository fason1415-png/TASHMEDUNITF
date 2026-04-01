<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $invoiceNo = 'INV-'.now()->format('Ym').'-'.$this->faker->unique()->numberBetween(1000, 9999);

        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'subscription_id' => Subscription::factory(),
            'invoice_number' => $invoiceNo,
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'amount_due' => $this->faker->randomFloat(2, 100000, 5000000),
            'amount_paid' => 0,
            'currency' => 'UZS',
            'status' => $this->faker->randomElement(['issued', 'paid', 'partial']),
            'due_date' => now()->addDays(15)->toDateString(),
            'paid_at' => null,
            'payment_reference' => null,
            'notes' => null,
            'meta' => null,
        ];
    }
}

