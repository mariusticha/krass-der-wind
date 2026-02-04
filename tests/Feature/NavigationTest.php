<?php

use App\Models\Gig;
use App\Models\User;

// Navigation Display Tests
test('navigation displays main links', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Gigs')
        ->assertSee('Songs')
        ->assertSee('Noten');
});

test('navigation displays brand logo and name', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Krass der Wind');
});

test('navigation contains about anchor link', function () {
    $response = $this->get(route('home'));

    $response->assertSee('About');
});

test('navigation links to gigs page', function () {
    $response = $this->get(route('home'));

    $response->assertSee(route('gigs.index'), false);
});

test('navigation links to songs page', function () {
    $response = $this->get(route('home'));

    $response->assertSee(route('songs.index'), false);
});

test('navigation has external link to noten', function () {
    $response = $this->get(route('home'));

    $response->assertSee('https://noethernetz.de/krassderwind/noten-fuer-krassderwind/', false)
        ->assertSee('target="_blank"', false);
});

test('navigation highlights active route for gigs', function () {
    $response = $this->get(route('gigs.index'));

    $response->assertSee('!text-amber-600', false);
});

test('navigation highlights active route for songs', function () {
    $response = $this->get(route('songs.index'));

    $response->assertSee('!text-amber-600', false);
});

// Guest Navigation Tests
test('navigation shows login link for guests', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Login');
});

test('navigation does not show user profile for guests', function () {
    $response = $this->get(route('home'));

    $response->assertDontSee('Dashboard')
        ->assertDontSee('Settings')
        ->assertDontSee('Log Out');
});

// Authenticated User Navigation Tests
test('navigation shows user profile for authenticated users', function () {
    $user = User::factory()->create(['name' => 'Test Musician']);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('Test Musician')
        ->assertDontSee('Login');
});

test('navigation shows user email in profile dropdown', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('test@example.com');
});

test('navigation shows dashboard link for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('Dashboard')
        ->assertSee(route('dashboard'), false);
});

test('navigation shows settings link for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('Settings')
        ->assertSee(route('profile.edit'), false);
});

test('navigation shows logout button for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('Log Out');
});

test('navigation displays user initials in profile button', function () {
    $user = User::factory()->create(['name' => 'Max Mustermann']);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('MM', false);
});

// Mobile Navigation Tests
test('navigation has mobile menu toggle', function () {
    $response = $this->get(route('home'));

    $response->assertSee('x-data="{ mobileMenuOpen: false }"', false);
});

// Scroll Behavior Tests
test('navigation uses scroll-based visibility on welcome page', function () {
    $response = $this->get(route('home'));

    $response->assertSee(':class="isWelcomePage', false)
        ->assertSee('translate-y', false);
});

test('navigation is always visible on non-welcome pages', function () {
    // Create a test gig to ensure the gigs page exists
    Gig::factory()->create();

    $response = $this->get(route('gigs.index'));

    // Navigation should still render with the conditional logic
    $response->assertSee('isWelcomePage', false)
        ->assertSee('translate-y-0 opacity-100', false);
});

// Local Development Helper
test('navigation shows quick login in local environment', function () {
    app()->detectEnvironment(fn() => 'local');

    $response = $this->get(route('home'));

    $response->assertSee('Quick Login', false);
});

test('navigation does not show quick login in production', function () {
    app()->detectEnvironment(fn() => 'production');

    $response = $this->get(route('home'));

    $response->assertDontSee('Quick Login');
});

// Styling Tests
test('navigation has backdrop blur styling', function () {
    $response = $this->get(route('home'));

    $response->assertSee('backdrop-blur-xl', false);
});

test('navigation is fixed positioned', function () {
    $response = $this->get(route('home'));

    $response->assertSee('fixed top-0', false);
});

test('navigation has proper z-index', function () {
    $response = $this->get(route('home'));

    $response->assertSee('z-50', false);
});
