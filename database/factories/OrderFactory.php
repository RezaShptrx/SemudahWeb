<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $totalPrice = $this->faker->randomFloat(2, 5000, 200000);
        return [
            'order_number' => 'SMDH-' . date('Ymd') . '-' . Str::upper(Str::random(4)),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => '628' . $this->faker->numerify('#########'),
            'status' => \App\Enums\OrderStatus::MENUNGGU_ANTRIAN,
            'total_price' => $totalPrice,
            'discount_amount' => 0,
            'final_price' => $totalPrice,
            'payment_method' => $this->faker->randomElement(['qris', 'tunai']),
            'payment_status' => 'pending',
            'notes' => $this->faker->sentence(),
            'internal_notes' => null,
            'estimated_completion_at' => null,
            'completed_at' => null,
            'taken_at' => null,
            'verified_by' => null,
        ];
    }
}
