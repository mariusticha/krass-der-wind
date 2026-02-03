<?php

use App\Models\Song;

test('song model has expected fillable attributes', function () {
    $fillable = (new Song())->getFillable();

    expect($fillable)->toContain('name')
        ->and($fillable)->toContain('artist')
        ->and($fillable)->toContain('year')
        ->and($fillable)->toContain('description');
});

test('song model uses soft deletes trait', function () {
    $traits = class_uses_recursive(Song::class);

    expect($traits)->toContain(\Illuminate\Database\Eloquent\SoftDeletes::class);
});

test('song model casts year to integer', function () {
    $casts = (new Song())->getCasts();

    expect($casts)->toHaveKey('year')
        ->and($casts['year'])->toBe('integer');
});
