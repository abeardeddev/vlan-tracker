<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_number' => '789'.$this->faker->randomNumber(8, true),
            'name' => $this->faker->name(),
            'location_id' => Location::inRandomOrder()->first()->id,
            'active' => $this->faker->boolean(80),
        ];
    }
}
