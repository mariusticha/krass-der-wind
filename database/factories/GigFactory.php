<?php

namespace Database\Factories;

use App\Models\Gig;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gig>
 */
class GigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $venues = [
            'Biergarten am See',
            'Stadtfesthalle',
            'Marktplatz',
            'Weinfest Pavillon',
            'Musikpavillon im Park',
            'Rathauskeller',
            'Festzelt Oktoberfest',
            'Schlosspark',
            'Alte Brauerei',
            'Kulturzentrum',
        ];

        $cities = [
            'München',
            'Hamburg',
            'Berlin',
            'Köln',
            'Frankfurt',
            'Stuttgart',
            'Düsseldorf',
            'Dortmund',
            'Essen',
            'Leipzig',
        ];

        $gigTypes = [
            'Stadtfest',
            'Hochzeit',
            'Sommerfest',
            'Oktoberfest',
            'Weihnachtsmarkt',
            'Geburtstag',
            'Firmenfeier',
            'Weinfest',
            'Konzert im Park',
            'Jubiläum',
        ];

        $songs = [
            'In München steht ein Hofbräuhaus',
            'Ein Prosit der Gemütlichkeit',
            'Beer Barrel Polka',
            'Böhmischer Traum',
            'Marsch der Medici',
            'Florentiner Marsch',
            'Alte Kameraden',
            'Freude schöner Götterfunken',
            'Radetzky Marsch',
            'Die lustigen Dorfschmiede',
            'Fliegermarsch',
            'Hoch Heidecksburg',
            'Stars and Stripes Forever',
            'Preußens Gloria',
            'Königgrätzer Marsch',
        ];

        $playlist = fake()->randomElements($songs, fake()->numberBetween(5, 10));

        return [
            'name' => fake()->randomElement($gigTypes) . ' ' . fake()->year(),
            'description' => fake()->boolean(70) ? fake()->paragraph() : null,
            'date' => fake()->dateTimeBetween('-6 months', '+6 months'),
            'time' => fake()->time(),
            'location' => fake()->randomElement($venues),
            'city' => fake()->randomElement($cities),
            'playlist' => $playlist,
            'is_public' => fake()->boolean(90),
        ];
    }

    public function past(): static
    {
        return $this->state(fn(array $attributes): array => [
            'date' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn(array $attributes): array => [
            'date' => fake()->dateTimeBetween('now', '+6 months'),
        ]);
    }

    public function public(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_public' => true,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_public' => false,
        ]);
    }
}
