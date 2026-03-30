<?php

use App\Livewire\Pages\Gigs\Edit;
use App\Livewire\Pages\Gigs\Index;
use App\Models\Gig;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can create a gig', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('name', 'Test Gig')
        ->set('date', '2026-06-15')
        ->set('time', '19:00')
        ->set('location', 'Test Venue')
        ->set('city', 'Berlin')
        ->set('description', 'A test gig')
        ->set('isPublic', true)
        ->call('save');

    expect(Gig::where('name', 'Test Gig')->exists())->toBeTrue();
});

test('authenticated users can edit a gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create([
        'name' => 'Original Name',
        'date' => '2026-07-01',
    ]);

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->assertSet('gigId', $gig->id)
        ->assertSet('name', 'Original Name')
        ->set('name', 'Updated Name')
        ->call('save');

    expect($gig->fresh()->name)->toBe('Updated Name');
});

test('authenticated users can delete a gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->upcoming()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->call('deleteGig', $gig->id)
        ->assertDispatched('gig-deleted');

    expect(Gig::find($gig->id))->toBeNull();
});

test('authenticated users can RSVP to a gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->upcoming()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->call('toggleRsvp', $gig->id);

    expect($gig->users()->where('user_id', $user->id)->first()->pivot->rsvp_status)
        ->toBe('yes');
});

test('authenticated users can remove their RSVP from a gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->upcoming()->create();

    $user->gigs()->attach($gig->id, ['rsvp_status' => 'yes']);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->call('toggleRsvp', $gig->id);

    expect($gig->users()->where('user_id', $user->id)->exists())->toBeFalse();
});

test('authenticated users can mark attendance for past gigs', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->past()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->call('toggleAttendance', $gig->id);

    expect($gig->users()->where('user_id', $user->id)->first()->pivot->attended)
        ->toBeTruthy();
});

test('guests can view public gigs but not edit them', function () {
    // Create fresh gigs for this test to avoid interference from seeded data
    Gig::query()->delete();

    $publicGig = Gig::factory()->public()->upcoming()->create(['name' => 'Public Test Gig']);
    $privateGig = Gig::factory()->private()->upcoming()->create(['name' => 'Private Test Gig']);

    Livewire::test(Index::class)
        ->assertSee('Public Test Gig')
        ->assertDontSee('Private Test Gig')
        ->assertDontSee('Edit')
        ->assertDontSee('Delete')
        ->assertDontSee('Create Gig');
});

test('a gig can be created with a link url', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('name', 'Link Gig')
        ->set('date', '2026-08-01')
        ->set('location', 'Venue')
        ->set('city', 'München')
        ->set('linkUrl', 'https://example.com/event')
        ->call('save');

    expect(Gig::where('name', 'Link Gig')->first()->link_url)->toBe('https://example.com/event');
});

test('a gig can be created without a link url', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('name', 'No Link Gig')
        ->set('date', '2026-08-02')
        ->set('location', 'Venue')
        ->set('city', 'München')
        ->call('save');

    expect(Gig::where('name', 'No Link Gig')->first()->link_url)->toBeNull();
});

test('an invalid url is rejected when saving a gig', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('name', 'Invalid Link Gig')
        ->set('date', '2026-08-03')
        ->set('location', 'Venue')
        ->set('city', 'München')
        ->set('linkUrl', 'not-a-valid-url')
        ->call('save')
        ->assertHasErrors(['linkUrl']);
});

test('link url is loaded when editing an existing gig', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create(['link_url' => 'https://example.com/existing']);

    Livewire::actingAs($user)
        ->test(Edit::class, ['gig' => $gig])
        ->assertSet('linkUrl', 'https://example.com/existing');
});


test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
