<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $artists = [
            'The Beatles',
            'Led Zeppelin',
            'Pink Floyd',
            'Queen',
            'The Rolling Stones',
            'AC/DC',
            'Metallica',
            'Nirvana',
            'Red Hot Chili Peppers',
            'Radiohead',
            'Foo Fighters',
            'Pearl Jam',
            'Coldplay',
            'Arctic Monkeys',
            'The Strokes',
        ];

        return [
            'name' => fake()->words(rand(1, 4), true),
            'artist' => fake()->randomElement($artists),
            'year' => fake()->optional(0.8)->numberBetween(1960, 2026),
            'description' => fake()->optional(0.3)->sentence(),
        ];
    }
}
