<?php

namespace Database\Factories;

use App\Models\Assembly;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['ended', 'ongoing', 'unavailable']),
            'description' => $this->faker->sentence(15),
            'from' => collect([null, $this->faker->date('Y-m-d', '2024-01-01')])->random(),
            'to' => collect([$this->faker->date('Y-m-d', '2028-08-01')])->random(),
            'user_id' => collect([null, ...User::get('id')])->random(),
        ];
    }
}
