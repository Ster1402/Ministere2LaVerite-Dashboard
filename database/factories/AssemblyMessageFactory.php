<?php

namespace Database\Factories;

use App\Models\Assembly;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssemblyMessage>
 */
class AssemblyMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assembly_id' => Assembly::get(['id'])->random(),
            'message_id' => Message::get(['id'])->random()
        ];
    }
}
