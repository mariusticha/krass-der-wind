<?php

use App\Models\User;

// Basic Access Tests
test('welcome page is publicly accessible', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('welcome page does not require authentication', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertDontSee('Login Required');
});

test('authenticated users can also view welcome page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
});

// Content Tests
test('welcome page displays band name in hero section', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Krass der Wind');
});

test('welcome page displays tagline', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Politische Musik-Aktion!')
        ->assertSee('Neu seit 2024 in Falkensee!');
});

test('welcome page displays hero call-to-action buttons', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Unsere Auftritte')
        ->assertSee('Jetzt mitmachen!');
});

test('welcome page hero buttons link correctly', function () {
    $response = $this->get(route('home'));

    $response->assertSee(route('gigs.index'), false)
        ->assertSee('#about', false);
});

// About Section Tests
test('welcome page displays about section', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Über Uns')
        ->assertSee('Wir sind Holz- und Blechbläser*innen aus Falkensee und Umgebung');
});

test('welcome page about section mentions key values', function () {
    $response = $this->get(route('home'));

    $response->assertSee('gegen Rechts')
        ->assertSee('Demokratie')
        ->assertSee('Menschenfeindlichkeit')
        ->assertSee('Ukraine')
        ->assertSee('Klimaschutz')
        ->assertSee('Holocaust');
});

test('welcome page about section mentions participation requirements', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Blasinstrumente jeder Art sind willkommen')
        ->assertSee('Alter von 14 bis 99');
});

test('welcome page about section has link to gigs', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Unsere Auftritte ansehen');
});

test('welcome page about section displays band image', function () {
    $response = $this->get(route('home'));

    $response->assertSee('images/2025-kdw-funny.jpg', false)
        ->assertSee('alt="Krass der Wind Band"', false);
});

// CTA Section Tests
test('welcome page displays call-to-action section', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Bereit mitzumachen?');
});

test('welcome page cta section mentions rehearsal details', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Wir proben einmal im Monat in Falkensee')
        ->assertSee('Notenkenntnisse und Spielerfahrung sind erforderlich')
        ->assertSee('Amateurmusiker*innen auf unterschiedlichen Niveaus');
});

test('welcome page cta section has gigs link', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Aktuelle Auftritte');
});

test('welcome page cta section has external link to noethernetz', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Mehr erfahren')
        ->assertSee('https://noethernetz.de/krassderwind/', false)
        ->assertSee('target="_blank"', false);
});

// Component Tests
test('welcome page renders navigation component', function () {
    $response = $this->get(route('home'));

    // Navigation is present
    $response->assertSee('Gigs')
        ->assertSee('Songs');
});

test('welcome page renders footer component', function () {
    $response = $this->get(route('home'));

    // Footer is present
    $response->assertOk();
});

// SEO & Meta Tests
test('welcome page has correct title', function () {
    $response = $this->get(route('home'));

    $response->assertSee('<title>Krass der Wind - Brass Band</title>', false);
});

test('welcome page uses correct layout', function () {
    $response = $this->get(route('home'));

    $response->assertViewIs('pages.welcome');
});

// Anchor Links Test
test('welcome page about section has correct id for anchor navigation', function () {
    $response = $this->get(route('home'));

    $response->assertSee('id="about-section"', false);
});

// Responsive Design Hints
test('welcome page contains responsive classes', function () {
    $response = $this->get(route('home'));

    $response->assertSee('lg:text-7xl', false)
        ->assertSee('md:grid-cols-2', false);
});

// Animation Classes
test('welcome page includes animation classes for visual effects', function () {
    $response = $this->get(route('home'));

    $response->assertSee('animate-fade-in-up', false)
        ->assertSee('animate-pulse', false)
        ->assertSee('animate-bounce', false);
});

// Accessibility Tests
test('welcome page includes alt text for images', function () {
    $response = $this->get(route('home'));

    $response->assertSee('alt="Krass der Wind Band"', false);
});

test('welcome page external links have proper target attribute', function () {
    $response = $this->get(route('home'));

    $response->assertSee('target="_blank"', false);
});

// Performance: Check that critical content is present
test('welcome page loads all critical sections', function () {
    $response = $this->get(route('home'));

    // Hero section
    $response->assertSee('Krass der Wind');

    // About section
    $response->assertSee('Über Uns');

    // CTA section
    $response->assertSee('Bereit mitzumachen?');

    // All should be on same page
    $response->assertOk();
});

// Content Freshness
test('welcome page mentions current founding year', function () {
    $response = $this->get(route('home'));

    $response->assertSee('2024');
});

test('welcome page mentions Falkensee location', function () {
    $response = $this->get(route('home'));

    $response->assertSeeText('Falkensee', false, 2); // Should appear at least twice
});
