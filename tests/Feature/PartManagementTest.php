<?php

use App\Livewire\Pages\Parts\Index as PartsIndex;
use App\Models\Part;
use App\Models\Sheet;
use App\Models\Song;
use App\Models\User;
use Livewire\Livewire;

test('part has songs relationship via sheets', function () {
    $part = Part::factory()->create();
    $song = Song::factory()->create();

    Sheet::create([
        'part_id' => $part->id,
        'song_id' => $song->id,
        'file_path' => 'sheets/test.pdf',
    ]);

    expect($part->songs)->toHaveCount(1)
        ->and($part->songs->first()->id)->toBe($song->id);
});

test('parts index loads songs_count for each part', function () {
    Part::query()->delete();

    $part = Part::factory()->create();
    $songs = Song::factory(3)->create();

    foreach ($songs as $song) {
        Sheet::create([
            'part_id' => $part->id,
            'song_id' => $song->id,
            'file_path' => 'sheets/test.pdf',
        ]);
    }

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(PartsIndex::class)
        ->assertViewHas('parts', function ($parts) {
            return $parts->first()->songs_count === 3;
        });
});

test('parts index shows correct song count in part card', function () {
    Part::query()->delete();

    $part = Part::factory()->create();
    $songs = Song::factory(2)->create();

    foreach ($songs as $song) {
        Sheet::create([
            'part_id' => $part->id,
            'song_id' => $song->id,
            'file_path' => 'sheets/test.pdf',
        ]);
    }

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(PartsIndex::class)
        ->assertSee('2');
});

test('parts index shows zero song count for part with no sheets', function () {
    Part::query()->delete();

    Part::factory()->create();

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(PartsIndex::class)
        ->assertViewHas('parts', function ($parts) {
            return $parts->first()->songs_count === 0;
        });
});
