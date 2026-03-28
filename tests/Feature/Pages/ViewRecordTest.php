<?php

use App\Livewire\Pages\Gigs\Index as GigsIndex;
use App\Livewire\Pages\Parts\Index as PartsIndex;
use App\Livewire\Pages\Songs\Index as SongsIndex;
use App\Models\Gig;
use App\Models\Part;
use App\Models\Song;
use App\Models\User;
use Livewire\Livewire;

// --- Gig view ---

test('url query parameter opens gig view modal on mount', function () {
    $gig = Gig::factory()->upcoming()->create();

    Livewire::test(GigsIndex::class, ['viewingId' => $gig->id])
        ->assertSet('viewingId', $gig->id)
        ->assertSet('showViewModal', true);
});

test('unauthenticated users can open gig view modal', function () {
    $gig = Gig::factory()->upcoming()->create(['is_public' => true]);

    Livewire::test(GigsIndex::class)
        ->call('viewRecord', $gig->id)
        ->assertSet('viewingId', $gig->id)
        ->assertSet('showViewModal', true);
});

test('viewRecord sets gig viewing state', function () {
    $gig = Gig::factory()->upcoming()->create();
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(GigsIndex::class)
        ->call('viewRecord', $gig->id)
        ->assertSet('viewingId', $gig->id)
        ->assertSet('showViewModal', true);
});

test('closing gig view modal clears viewingId', function () {
    $gig = Gig::factory()->upcoming()->create();
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(GigsIndex::class)
        ->call('viewRecord', $gig->id)
        ->set('showViewModal', false)
        ->assertSet('viewingId', null)
        ->assertSet('viewingGig', null);
});

// --- Song view ---

test('url query parameter opens song view modal on mount', function () {
    $song = Song::factory()->create();

    Livewire::test(SongsIndex::class, ['viewingId' => $song->id])
        ->assertSet('viewingId', $song->id)
        ->assertSet('showViewModal', true);
});

test('unauthenticated users can open song view modal', function () {
    $song = Song::factory()->create();

    Livewire::test(SongsIndex::class)
        ->call('viewRecord', $song->id)
        ->assertSet('viewingId', $song->id)
        ->assertSet('showViewModal', true);
});

test('viewRecord sets song viewing state', function () {
    $song = Song::factory()->create();
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->call('viewRecord', $song->id)
        ->assertSet('viewingId', $song->id)
        ->assertSet('showViewModal', true);
});

test('closing song view modal clears viewingId', function () {
    $song = Song::factory()->create();
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(SongsIndex::class)
        ->call('viewRecord', $song->id)
        ->set('showViewModal', false)
        ->assertSet('viewingId', null);
});

// --- Part view ---

test('url query parameter opens part view modal on mount', function () {
    $user = User::factory()->create();
    $part = Part::factory()->create();

    Livewire::actingAs($user)
        ->test(PartsIndex::class, ['viewingId' => $part->id])
        ->assertSet('viewingId', $part->id)
        ->assertSet('showViewModal', true);
});

test('viewRecord sets part viewing state', function () {
    $user = User::factory()->create();
    $part = Part::factory()->create();

    Livewire::actingAs($user)
        ->test(PartsIndex::class)
        ->call('viewRecord', $part->id)
        ->assertSet('viewingId', $part->id)
        ->assertSet('showViewModal', true);
});

test('closing part view modal clears viewingId', function () {
    $user = User::factory()->create();
    $part = Part::factory()->create();

    Livewire::actingAs($user)
        ->test(PartsIndex::class)
        ->call('viewRecord', $part->id)
        ->set('showViewModal', false)
        ->assertSet('viewingId', null);
});

test('song view modal renders sheets for song', function () {
    $song = Song::factory()->create();
    $part = Part::factory()->create();
    $song->sheets()->create(['part_id' => $part->id, 'file_path' => 'sheets/test.pdf']);

    Livewire::test(SongsIndex::class)
        ->call('viewRecord', $song->id)
        ->assertSee($part->name);
});

test('part view modal renders songs using that part', function () {
    $user = User::factory()->create();
    $song = Song::factory()->create();
    $part = Part::factory()->create();
    $song->sheets()->create(['part_id' => $part->id, 'file_path' => 'sheets/test.pdf']);

    Livewire::actingAs($user)
        ->test(PartsIndex::class)
        ->call('viewRecord', $part->id)
        ->assertSee($song->name);
});
