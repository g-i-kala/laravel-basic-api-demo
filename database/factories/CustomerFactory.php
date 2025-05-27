<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['I', 'B']);
        $name = $type == 'I' ? fake()->name() : fake()->company();
        $user = User::inRandomOrder()->first()?->id ?? User::factory();

        return [
            'user_id' => $user,
            'name' => $name,
            'type' => $type,
            'email' => fake()->email(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
        ];
    }
}
