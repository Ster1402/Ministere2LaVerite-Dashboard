<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->title,
            'uri' => $this->faker->url,
            'type' => $this->faker->randomElement(['pdf', 'image', 'video']),
            'user_id' => User::get('id')->random(),
            'sender_id' => User::get('id')->random(),
        ];
    }
}
