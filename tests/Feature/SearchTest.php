<?php

use App\Livewire\Pages\Parts\Index as PartsIndex;
use App\Livewire\Pages\Songs\Index as SongsIndex;
use App\Models\Part;
use App\Models\Song;
use Livewire\Livewire;

// ──────────────────────────────────────────────
// Songs index search
// ──────────────────────────────────────────────

test('songs index shows all songs when search is empty', function () {
    Song::query()->delete();

    Song::factory()->create(['name' => 'Yesterday', 'artist' => 'The Beatles']);
    Song::factory()->create(['name' => 'Bohemian Rhapsody', 'artist' => 'Queen']);

    Livewire::test(SongsIndex::class)
        ->assertViewHas('songs', fn($songs) => $songs->count() === 2);
});

test('songs index filters by name', function () {
    Song::query()->delete();

    Song::factory()->create(['name' => 'Yesterday', 'artist' => 'The Beatles']);
    Song::factory()->create(['name' => 'Bohemian Rhapsody', 'artist' => 'Queen']);

    Livewire::test(SongsIndex::class)
        ->set('search', 'yesterday')
        ->assertViewHas('songs', fn($songs) => $songs->count() === 1 && $songs->first()->name === 'Yesterday');
});

test('songs index filters by artist', function () {
    Song::query()->delete();

    Song::factory()->create(['name' => 'Yesterday', 'artist' => 'The Beatles']);
    Song::factory()->create(['name' => 'Bohemian Rhapsody', 'artist' => 'Queen']);

    Livewire::test(SongsIndex::class)
        ->set('search', 'Queen')
        ->assertViewHas('songs', fn($songs) => $songs->count() === 1 && $songs->first()->artist === 'Queen');
});

test('songs index returns empty when search matches nothing', function () {
    Song::query()->delete();

    Song::factory()->create(['name' => 'Yesterday', 'artist' => 'The Beatles']);

    Livewire::test(SongsIndex::class)
        ->set('search', 'xyznotfound')
        ->assertViewHas('songs', fn($songs) => $songs->isEmpty());
});

test('songs index search is url-persisted', function () {
    Song::query()->delete();

    Song::factory()->create(['name' => 'Yesterday', 'artist' => 'The Beatles']);
    Song::factory()->create(['name' => 'Bohemian Rhapsody', 'artist' => 'Queen']);

    Livewire::withQueryParams(['search' => 'Queen'])
        ->test(SongsIndex::class)
        ->assertSet('search', 'Queen')
        ->assertViewHas('songs', fn($songs) => $songs->count() === 1 && $songs->first()->artist === 'Queen');
});

test('songs index search resets pagination', function () {
    Song::query()->delete();

    Song::factory(15)->sequence(fn($seq) => ['name' => 'Song ' . $seq->index, 'artist' => 'Artist A'])->create();
    Song::factory()->create(['artist' => 'The Beatles', 'name' => 'Yesterday']);

    Livewire::test(SongsIndex::class)
        ->set('search', 'beatles')
        ->assertViewHas('songs', fn($songs) => $songs->count() === 1 && $songs->first()->artist === 'The Beatles');
});

// ──────────────────────────────────────────────
// Parts index search
// ──────────────────────────────────────────────

test('parts index shows all parts when search is empty', function () {
    Part::query()->delete();

    Part::create(['name' => 'Guitar']);
    Part::create(['name' => 'Drums']);

    Livewire::test(PartsIndex::class)
        ->assertViewHas('parts', fn($parts) => $parts->count() === 2);
});

test('parts index filters by name', function () {
    Part::query()->delete();

    Part::create(['name' => 'Guitar']);
    Part::create(['name' => 'Drums']);

    Livewire::test(PartsIndex::class)
        ->set('search', 'guitar')
        ->assertViewHas('parts', fn($parts) => $parts->count() === 1 && $parts->first()->name === 'Guitar');
});

test('parts index returns empty when search matches nothing', function () {
    Part::query()->delete();

    Part::create(['name' => 'Guitar']);

    Livewire::test(PartsIndex::class)
        ->set('search', 'xyznotfound')
        ->assertViewHas('parts', fn($parts) => $parts->isEmpty());
});

test('parts index search is url-persisted', function () {
    Part::query()->delete();

    Part::create(['name' => 'Guitar']);
    Part::create(['name' => 'Drums']);

    Livewire::withQueryParams(['search' => 'drums'])
        ->test(PartsIndex::class)
        ->assertSet('search', 'drums')
        ->assertViewHas('parts', fn($parts) => $parts->count() === 1 && $parts->first()->name === 'Drums');
});
