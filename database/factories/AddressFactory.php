<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'fullname'     => fake()->name(),
            'phone'        => fake()->phoneNumber(),
            'address_line' => fake()->streetAddress(),
            'province'     => fake()->city(),
            'district'     => fake()->streetName(),
            'zipcode'      => fake()->postcode(),
            'is_default'   => false,
        ];
    }
}
