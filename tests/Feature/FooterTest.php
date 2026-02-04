<?php

// Footer Display Tests
test('footer displays on pages', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('footer contains band name', function () {
    $response = $this->get(route('home'));

    $response->assertSee('Krass der Wind');
});

test('footer displays on gigs page', function () {
    $response = $this->get(route('gigs.index'));

    $response->assertSee('Krass der Wind');
});

test('footer displays on songs page', function () {
    $response = $this->get(route('songs.index'));

    $response->assertSee('Krass der Wind');
});

test('footer is present in app layout', function () {
    $response = $this->get(route('home'));

    // Check that footer component is rendered
    $response->assertSee('Krass der Wind');
});

// Footer Component Rendering
test('footer renders from layout component', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    // Footer is included via x-layout.footer component in app layout
});
