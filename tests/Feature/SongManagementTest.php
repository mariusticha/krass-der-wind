<?php

use App\Livewire\Pages\Gigs\Edit;
use App\Livewire\Pages\Gigs\Index;
use App\Livewire\Pages\Songs\Edit as SongsEdit;
use App\Livewire\Pages\Songs\Index as SongsIndex;
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

// Songs Pages Tests

test('songs index page is publicly accessible', function () {
    $response = $this->get('/songs');

    $response->assertStatus(200);
});

test('songs index page displays all songs with pagination', function () {
    Song::query()->delete();

    // Create 15 songs to test pagination (10 per page)
    Song::factory(15)->create();

    Livewire::test(SongsIndex::class)
        ->assertViewHas('songs', function ($songs) {
            return $songs->count() === 10; // First page should have 10
        })
        ->assertSee('Next'); // Should show pagination
});

test('songs index shows songs ordered by artist and name', function () {
    Song::query()->delete();

    Song::factory()->create(['artist' => 'Zebra', 'name' => 'Zulu']);
    Song::factory()->create(['artist' => 'Alpha', 'name' => 'Beta']);
    Song::factory()->create(['artist' => 'Alpha', 'name' => 'Alpha']);

    $component = Livewire::test(SongsIndex::class);

    $songs = $component->viewData('songs');

    expect($songs->first()->artist)->toBe('Alpha')
        ->and($songs->first()->name)->toBe('Alpha')
        ->and($songs->last()->artist)->toBe('Zebra');
});

test('guests can see songs index but not create button', function () {
    Song::query()->delete();

    $song = Song::factory()->create(['name' => 'Test Public Song']);

    Livewire::test(SongsIndex::class)
        ->assertSee('Test Public Song')
        ->assertDontSee('Create Song');
});

test('authenticated users can see create button on songs index', function () {
    $user = User::factory()->create();
    Song::query()->delete();

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->assertSee('Create Song');
});

test('guests can view song details but not private information', function () {
    Song::query()->delete();

    $song = Song::factory()->create([
        'name' => 'Test Song',
        'artist' => 'Test Artist',
        'year' => 2020,
        'description' => 'Private description',
    ]);

    Livewire::test(SongsIndex::class)
        ->assertSee('Test Song')
        ->assertSee('Test Artist')
        ->assertSee('2020')
        ->assertDontSee('Private description') // Guests shouldn't see description
        ->assertDontSee('Edit')
        ->assertDontSee('Delete');
});

test('authenticated users can see all song information including private data', function () {
    $user = User::factory()->create();
    Song::query()->delete();

    $song = Song::factory()->create([
        'name' => 'Test Song',
        'artist' => 'Test Artist',
        'description' => 'Internal notes',
    ]);

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->assertSee('Test Song')
        ->assertSee('Internal notes')
        ->assertSee('Edit')
        ->assertSee('Delete');
});

test('authenticated users can see gig count for songs', function () {
    $user = User::factory()->create();
    Song::query()->delete();

    $song = Song::factory()->create(['name' => 'Popular Song']);
    $gigs = Gig::factory(3)->create();

    foreach ($gigs as $gig) {
        $gig->songs()->attach($song->id);
    }

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->assertSee('3 gigs'); // Should show gig count
});

test('songs create page requires authentication', function () {
    $response = $this->get('/songs/create');

    $response->assertRedirect('/login');
});

test('authenticated users can access songs create page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/songs/create')
        ->assertStatus(200);
});

test('authenticated users can create a song', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsEdit::class)
        ->set('name', 'New Song')
        ->set('artist', 'New Artist')
        ->set('year', 2024)
        ->set('description', 'A new song')
        ->call('save')
        ->assertRedirect('/songs');

    expect(Song::where('name', 'New Song')->exists())->toBeTrue()
        ->and(Song::where('name', 'New Song')->first()->artist)->toBe('New Artist')
        ->and(Song::where('name', 'New Song')->first()->year)->toBe(2024);
});

test('song creation validates required fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsEdit::class)
        ->set('name', '')
        ->set('artist', '')
        ->call('save')
        ->assertHasErrors(['name', 'artist']);
});

test('song creation validates year range', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsEdit::class)
        ->set('name', 'Test Song')
        ->set('artist', 'Test Artist')
        ->set('year', 1800) // Too old
        ->call('save')
        ->assertHasErrors(['year']);

    Livewire::actingAs($user)
        ->test(SongsEdit::class)
        ->set('name', 'Test Song')
        ->set('artist', 'Test Artist')
        ->set('year', 2200) // Too far in future
        ->call('save')
        ->assertHasErrors(['year']);
});

test('songs edit page requires authentication', function () {
    $song = Song::factory()->create();

    $response = $this->get("/songs/{$song->id}/edit");

    $response->assertRedirect('/login');
});

test('authenticated users can access songs edit page', function () {
    $user = User::factory()->create();
    $song = Song::factory()->create();

    $this->actingAs($user)
        ->get("/songs/{$song->id}/edit")
        ->assertStatus(200);
});

test('authenticated users can edit a song', function () {
    $user = User::factory()->create();
    $song = Song::factory()->create([
        'name' => 'Original Name',
        'artist' => 'Original Artist',
        'year' => 2020,
    ]);

    Livewire::actingAs($user)
        ->test(SongsEdit::class, ['song' => $song])
        ->assertSet('songId', $song->id)
        ->assertSet('name', 'Original Name')
        ->set('name', 'Updated Name')
        ->set('artist', 'Updated Artist')
        ->set('year', 2025)
        ->call('save')
        ->assertRedirect('/songs');

    expect($song->fresh()->name)->toBe('Updated Name')
        ->and($song->fresh()->artist)->toBe('Updated Artist')
        ->and($song->fresh()->year)->toBe(2025);
});

test('authenticated users can delete a song', function () {
    $user = User::factory()->create();
    $song = Song::factory()->create(['name' => 'Song to Delete']);

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->call('deleteSong', $song->id)
        ->assertDispatched('song-deleted');

    expect(Song::find($song->id))->toBeNull();
});

test('deleting a song removes it from all gigs', function () {
    $user = User::factory()->create();
    $song = Song::factory()->create();
    $gig = Gig::factory()->create();

    $gig->songs()->attach($song->id);

    expect($gig->songs)->toHaveCount(1);

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->call('deleteSong', $song->id);

    $gig->refresh();

    expect($gig->songs)->toHaveCount(0)
        ->and(Song::find($song->id))->toBeNull();
});

test('song card shows warning when deleting song used in gigs', function () {
    $user = User::factory()->create();
    Song::query()->delete();

    $song = Song::factory()->create(['name' => 'Song in Gigs']);
    $gigs = Gig::factory(5)->create();

    foreach ($gigs as $gig) {
        $gig->songs()->attach($song->id);
    }

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->assertSee('Song in Gigs')
        ->assertSee('5 gigs'); // Should show gig count as warning context
});

test('cancel button on edit page redirects to songs index', function () {
    $user = User::factory()->create();
    $song = Song::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsEdit::class, ['song' => $song])
        ->call('cancel')
        ->assertRedirect('/songs');
});

test('cancel button on create page redirects to songs index', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsEdit::class)
        ->call('cancel')
        ->assertRedirect('/songs');
});

test('songs index displays empty state when no songs exist', function () {
    Song::query()->delete();

    Livewire::test(SongsIndex::class)
        ->assertSee('No songs yet');
});

test('songs index page shows correct titles for guests vs authenticated users', function () {
    Song::query()->delete();

    // Guest view
    Livewire::test(SongsIndex::class)
        ->assertSee('Browse our song repertoire');

    // Authenticated view
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->assertSee('Manage your band\'s song collection');
});
