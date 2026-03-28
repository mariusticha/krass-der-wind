<?php

namespace Database\Factories;

use App\Models\Part;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $instruments = [
            'Guitar',
            'Bass',
            'Drums',
            'Keyboard',
            'Vocals',
            'Trumpet',
            'Trombone',
            'Saxophone',
            'Violin',
            'Flute',
        ];

        return [
            'name' => fake()->unique()->randomElement($instruments),
        ];
    }
}
