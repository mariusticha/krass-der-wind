<?php

namespace Database\Seeders;

use App\Models\Gig;
use App\Models\Song;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 band members/musicians
        $users = collect([
            User::factory()->create([
                'name' => 'Max Mustermann',
                'email' => 'max@krass-der-wind.de',
                'instrument' => 'Trumpet',
            ]),
            User::factory()->create([
                'name' => 'Anna Schmidt',
                'email' => 'anna@krass-der-wind.de',
                'instrument' => 'Clarinet',
            ]),
            User::factory()->create([
                'name' => 'Thomas Müller',
                'email' => 'thomas@krass-der-wind.de',
                'instrument' => 'Trombone',
            ]),
            User::factory()->create([
                'name' => 'Lisa Weber',
                'email' => 'lisa@krass-der-wind.de',
                'instrument' => 'Saxophone',
            ]),
            User::factory()->create([
                'name' => 'Michael Bauer',
                'email' => 'michael@krass-der-wind.de',
                'instrument' => 'Tuba',
            ]),
        ]);

        // Add 15 more musicians
        $additionalUsers = User::factory(15)->create();
        $users = $users->merge($additionalUsers);

        // Create a realistic song library (50 songs)
        $songs = Song::factory(50)->create();

        // Create 5 past gigs with attendance
        $pastGigs = Gig::factory(5)->past()->public()->create();

        foreach ($pastGigs as $gig) {
            // More people attended (8-15 people played)
            $attendees = $users->random(fake()->numberBetween(8, 15));
            foreach ($attendees as $user) {
                $gig->users()->attach($user->id, [
                    'attended' => true,
                    'attended_at' => $gig->date->addHours(fake()->numberBetween(1, 4)),
                ]);
            }

            // Add 8-15 songs to each past gig with order
            $gigSongs = $songs->random(fake()->numberBetween(8, 15));
            foreach ($gigSongs->values() as $index => $song) {
                $gig->songs()->attach($song->id, [
                    'order' => $index + 1,
                    'notes' => fake()->optional(0.2)->randomElement([
                        'Crowd favorite',
                        'Extended solo',
                        'Acoustic version',
                        'Slower tempo',
                        'Sing-along',
                    ]),
                ]);
            }
        }

        // Create 5 upcoming gigs with RSVPs
        $upcomingGigs = Gig::factory(5)->upcoming()->create();

        foreach ($upcomingGigs as $gig) {
            // More RSVPs (10-18 people responded)
            $rsvpUsers = $users->random(fake()->numberBetween(10, 18));
            foreach ($rsvpUsers as $user) {
                $gig->users()->attach($user->id, [
                    'rsvp_status' => fake()->randomElement(['yes', 'yes', 'yes', 'no']), // 75% yes, 25% no
                    'rsvp_at' => now()->subDays(fake()->numberBetween(1, 14)),
                ]);
            }

            // Add 10-18 songs to each upcoming gig with order
            $gigSongs = $songs->random(fake()->numberBetween(10, 18));
            foreach ($gigSongs->values() as $index => $song) {
                $gig->songs()->attach($song->id, [
                    'order' => $index + 1,
                    'notes' => fake()->optional(0.15)->randomElement([
                        'Opening song',
                        'Finale',
                        'Try new arrangement',
                        'Keep it short',
                    ]),
                ]);
            }
        }

        // Create 2 private upcoming gigs (band practice, internal events)
        $privateGigs = Gig::factory(2)->upcoming()->private()->create();

        foreach ($privateGigs as $gig) {
            // More people for private events too (6-12)
            $rsvpUsers = $users->random(fake()->numberBetween(6, 12));
            foreach ($rsvpUsers as $user) {
                $gig->users()->attach($user->id, [
                    'rsvp_status' => 'yes',
                    'rsvp_at' => now()->subDays(fake()->numberBetween(1, 7)),
                ]);
            }

            // Add 5-10 songs to practice gigs without order (unordered practice list)
            $gigSongs = $songs->random(fake()->numberBetween(5, 10));
            foreach ($gigSongs as $song) {
                $gig->songs()->attach($song->id, [
                    'order' => null,
                    'notes' => fake()->optional(0.3)->randomElement([
                        'Needs practice',
                        'New song',
                        'Work on transitions',
                    ]),
                ]);
            }
        }
    }
}
