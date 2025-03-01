<?php

namespace Database\Factories;

use App\Models\Assembly;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssemblyEvent>
 */
class AssemblyEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assembly_id' => Assembly::get('id')->random(),
            'event_id' => Event::get('id')->random(),
        ];
    }
}
