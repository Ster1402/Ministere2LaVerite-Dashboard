<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Baptism>
 */
class BaptismFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::get('id')->random(),
            'type' => $this->faker->randomElement([
                'none', 'water', 'holy-spirit', 'both-water-and-holy-spirit']),
            'nominalMaker' => $this->faker->randomElement(['priest', 'arch']),
            'hasHolySpirit' => true,
            'spiritualLevel' => $this->faker->numberBetween(0, 10),
            'date_water' => $this->faker->date,
            'date_holy_spirit' => $this->faker->date,
            'date_latest' => $this->faker->date,
        ];
    }
}
