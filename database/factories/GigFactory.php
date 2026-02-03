<?php

namespace Database\Factories;

use App\Models\Gig;
use Carbon\Carbon;
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
            'Frühlingsfest',
            'Herbstfest',
            'Dorffest',
            'Schützenfest',
            'Volksfest',
            'Kirchweih',
            'Silvesterfeier',
            'Faschingsball',
            'Maifest',
            'Serenata',
            'Gartenparty',
            'Betriebsfeier',
            'Vereinsfest',
            'Jubiläumsfeier',
            'Tag der offenen Tür',
            'Kulturveranstaltung',
            'Open Air Konzert',
            'Benefizkonzert',
            'Erntedankfest',
            'Seefest',
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

        $date = fake()->dateTimeBetween('-6 months', '+6 months');
        $city = fake()->randomElement($cities);

        // Generate unique gig name with safety check
        $gigType = fake()->randomElement($gigTypes);
        $year = Carbon::parse($date)->format('Y');
        $name = $gigType . ' ' . $city . ' ' . $year;

        // If name exists, add a counter suffix
        $counter = 1;
        while (Gig::where('name', $name)->exists()) {
            $counter++;
            $name = $gigType . ' ' . $city . ' ' . $year . ' #' . $counter;
        }

        return [
            'name' => $name,
            'description' => fake()->boolean(70) ? fake()->paragraph() : null,
            'date' => $date,
            'time' => fake()->time(),
            'location' => fake()->randomElement($venues),
            'city' => $city,
            'is_public' => fake()->boolean(90),
        ];
    }

    public function past(): static
    {
        $newDate = fake()->dateTimeBetween('-1 year', '-1 day');

        return $this->state(fn(array $attributes): array => [
            'name' => str($attributes['name'])->replace(
                Carbon::parse($attributes['date'])->format('Y'),
                Carbon::parse($newDate)->format('Y'),
            )->value(),
            'date' => $newDate,
        ]);
    }

    public function upcoming(): static
    {
        $newDate = fake()->dateTimeBetween('now', '+6 months');

        return $this->state(fn(array $attributes): array => [
            'name' => str($attributes['name'])->replace(
                Carbon::parse($attributes['date'])->format('Y'),
                Carbon::parse($newDate)->format('Y'),
            )->value(),
            'date' => $newDate,
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
