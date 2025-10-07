<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->lexify('BOOK???')),
            'type' => $this->faker->randomElement(['fixed','percent']),
            'value' => $this->faker->randomFloat(2, 5, 20), // 👈 ใช้ 'value' แทน 'discount'
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'usage_limit' => $this->faker->numberBetween(1, 50),
            'used_count' => 0,
        ];
    }
}
