<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowed>
 */
class BorrowedFactory extends Factory
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
            'resource_id' => Resource::get('id')->random(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'borrowed_at' => $this->faker->date,
            'returned_at' => collect([null, $this->faker->date('Y-m-d', '2024-03-10')])->random(),
        ];
    }
}
