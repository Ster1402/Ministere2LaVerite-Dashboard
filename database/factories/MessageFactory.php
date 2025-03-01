<?php

namespace Database\Factories;

use App\Models\Assembly;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $forAssembly = collect([true, false])->random();
        return [
            'subject' => $this->faker->sentence,
            'content' => $this->faker->sentence(10),
            'picture_path' => $this->faker->imageUrl,
            'tags' => $this->faker->sentence(5),
            'senderId' => User::get('id')->random(),
            'receiverId' => $forAssembly ? null : User::get('id')->random(),
            'category' => $this->faker->word,
            'received' => true,
            'seen' => false,
        ];
    }
}
