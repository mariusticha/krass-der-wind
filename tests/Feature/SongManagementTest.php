<?php

use App\Livewire\Pages\Gigs\Edit;
use App\Livewire\Pages\Gigs\Index;
use App\Models\Gig;
use App\Models\Song;
use App\Models\User;
use Livewire\Livewire;

// Model tests
test('song factory creates valid songs', function () {
    $song = Song::factory()->create();

    expect($song->name)->toBeString()
        ->and($song->artist)->toBeString()
        ->and($song)->toBeInstanceOf(Song::class);
});

test('song can be created with all attributes', function () {
    $song = Song::create([
        'name' => 'Bohemian Rhapsody',
        'artist' => 'Queen',
        'year' => 1975,
        'description' => 'A rock opera',
    ]);

    expect($song->name)->toBe('Bohemian Rhapsody')
        ->and($song->artist)->toBe('Queen')
        ->and($song->year)->toBe(1975)
        ->and($song->description)->toBe('A rock opera');
});

test('song name and artist must be unique together', function () {
    Song::create([
        'name' => 'Test Song',
        'artist' => 'Test Artist',
    ]);

    Song::create([
        'name' => 'Test Song',
        'artist' => 'Test Artist',
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('song can have same name with different artist', function () {
    Song::create([
        'name' => 'Yesterday',
        'artist' => 'The Beatles',
    ]);

    $song = Song::create([
        'name' => 'Yesterday',
        'artist' => 'Boyz II Men',
    ]);

    expect($song)->toBeInstanceOf(Song::class)
        ->and(Song::where('name', 'Yesterday')->count())->toBe(2);
});

test('song uses soft deletes', function () {
    $song = Song::factory()->create();
    $songId = $song->id;

    $song->delete();

    expect(Song::find($songId))->toBeNull()
        ->and(Song::withTrashed()->find($songId))->not->toBeNull()
        ->and(Song::withTrashed()->find($songId)->trashed())->toBeTrue();
});

test('song has many to many relationship with gigs', function () {
    $song = Song::factory()->create();
    $gig = Gig::factory()->create();

    $gig->songs()->attach($song->id, [
        'order' => 1,
        'notes' => 'Opening song',
    ]);

    expect($song->gigs)->toHaveCount(1)
        ->and($song->gigs->first()->id)->toBe($gig->id)
        ->and($song->gigs->first()->pivot->order)->toBe(1)
        ->and($song->gigs->first()->pivot->notes)->toBe('Opening song');
});

// Relationship tests

test('gig can have multiple songs', function () {
    $gig = Gig::factory()->create();
    $songs = Song::factory(3)->create();

    $gig->songs()->attach($songs->pluck('id'));

    expect($gig->songs)->toHaveCount(3)
        ->and($gig->songs->first())->toBeInstanceOf(Song::class);
});

test('songs can be ordered in a gig', function () {
    $gig = Gig::factory()->create();
    $song1 = Song::factory()->create(['name' => 'First Song']);
    $song2 = Song::factory()->create(['name' => 'Second Song']);
    $song3 = Song::factory()->create(['name' => 'Third Song']);

    $gig->songs()->attach([
        $song1->id => ['order' => 1],
        $song2->id => ['order' => 2],
        $song3->id => ['order' => 3],
    ]);

    $orderedSongs = $gig->songs;

    expect($orderedSongs->first()->name)->toBe('First Song')
        ->and($orderedSongs->last()->name)->toBe('Third Song');
});

test('songs can have notes in a gig', function () {
    $gig = Gig::factory()->create();
    $song = Song::factory()->create();

    $gig->songs()->attach($song->id, [
        'notes' => 'Play acoustic version',
    ]);

    expect($gig->songs->first()->pivot->notes)->toBe('Play acoustic version');
});

test('user can search for existing songs', function () {
    $user = User::factory()->create();
    Song::factory()->create(['name' => 'Stairway to Heaven', 'artist' => 'Led Zeppelin']);
    Song::factory()->create(['name' => 'Highway to Hell', 'artist' => 'AC/DC']);

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('songSearch', 'Heaven')
        ->assertSee('Stairway to Heaven')
        ->assertDontSee('Highway to Hell');
});

test('user can add existing song to gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song = Song::factory()->create(['name' => 'Test Song', 'artist' => 'Test Artist']);

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song->id)
        ->assertSet('selectedSongs', function ($songs) use ($song) {
            return count($songs) === 1 && $songs[0]['id'] === $song->id;
        });
});

test('user can create new song and add it to gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('openAddSongModal')
        ->assertSet('showAddSongModal', true)
        ->set('newSongName', 'New Test Song')
        ->set('newSongArtist', 'New Test Artist')
        ->set('newSongYear', 2025)
        ->set('newSongDescription', 'A brand new song')
        ->call('createAndAddSong')
        ->assertSet('showAddSongModal', false);

    expect(Song::where('name', 'New Test Song')->exists())->toBeTrue();
});

test('user can remove song from gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song = Song::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song->id)
        ->call('removeSelectedSong', 0)
        ->assertSet('selectedSongs', []);
});

test('user can toggle ordered setlist', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song1 = Song::factory()->create();
    $song2 = Song::factory()->create();

    $component = Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song1->id)
        ->call('addSelectedSong', $song2->id)
        ->assertSet('isOrdered', false);

    // Enable ordering
    $component->set('isOrdered', true)
        ->assertSet('selectedSongs.0.order', 1)
        ->assertSet('selectedSongs.1.order', 2);

    // Disable ordering
    $component->set('isOrdered', false)
        ->assertSet('selectedSongs.0.order', null)
        ->assertSet('selectedSongs.1.order', null);
});

test('user can reorder songs with drag and drop', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song1 = Song::factory()->create();
    $song2 = Song::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song1->id)
        ->call('addSelectedSong', $song2->id)
        ->set('isOrdered', true)
        ->call('updateSongOrder', [$song2->id, $song1->id])
        ->assertSet('selectedSongs.0.id', $song2->id)
        ->assertSet('selectedSongs.0.order', 1)
        ->assertSet('selectedSongs.1.id', $song1->id)
        ->assertSet('selectedSongs.1.order', 2);
});

test('user can add notes to songs in gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song = Song::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song->id)
        ->set('selectedSongs.0.notes', 'Extended intro')
        ->assertSet('selectedSongs.0.notes', 'Extended intro');
});

test('gig displays songs in gig card', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song = Song::factory()->create(['name' => 'Test Display Song', 'artist' => 'Test Display Artist']);

    $gig->songs()->attach($song->id);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('Test Display Song')
        ->assertSee('Test Display Artist');
});

test('gig displays ordered songs with numbers', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->upcoming()->create();
    $song1 = Song::factory()->create(['name' => 'Song One']);
    $song2 = Song::factory()->create(['name' => 'Song Two']);

    $gig->songs()->attach([
        $song1->id => ['order' => 1],
        $song2->id => ['order' => 2],
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('Song One')
        ->assertSee('Song Two');
});

test('creating new song validates required fields', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('openAddSongModal')
        ->set('newSongName', '')
        ->set('newSongArtist', '')
        ->call('createAndAddSong')
        ->assertHasErrors(['newSongName', 'newSongArtist']);
});

test('cannot add duplicate song to same gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song = Song::factory()->create();

    $component = Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song->id)
        ->assertSet('selectedSongs', function ($songs) {
            return count($songs) === 1;
        });

    // Try to add same song again
    $component->call('addSelectedSong', $song->id)
        ->assertSet('selectedSongs', function ($songs) {
            return count($songs) === 1; // Should still be 1
        });
});

test('songs persist when saving gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create();
    $song = Song::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->call('addSelectedSong', $song->id)
        ->set('selectedSongs.0.notes', 'Test note')
        ->set('isOrdered', true)
        ->call('save');

    $gig->refresh();

    expect($gig->songs)->toHaveCount(1)
        ->and($gig->songs->first()->id)->toBe($song->id)
        ->and($gig->songs->first()->pivot->order)->toBe(1)
        ->and($gig->songs->first()->pivot->notes)->toBe('Test note');
});
