<?php

use App\Livewire\Pages\Gigs\Edit as GigsEdit;
use App\Livewire\Pages\Gigs\Index as GigsIndex;
use App\Livewire\Pages\Songs\Edit as SongsEdit;
use App\Livewire\Pages\Songs\Index as SongsIndex;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auto-login for local development
Route::get('/login', function () {
    if (request()->has('auto_login') && app()->environment('local')) {
        $user = User::first();
        if ($user) {
            Auth::login($user);
            return redirect()->back();
        }
    }
    return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->create(request());
})->name('login');

Route::view('/', 'pages.welcome')->name('home');

Route::livewire('gigs', GigsIndex::class)->name('gigs.index');
Route::livewire('songs', SongsIndex::class)->name('songs.index');

Route::view('imprint', 'pages.imprint', config('app.legals'))->name('imprint');
Route::view('privacy', 'pages.privacy', config('app.legals'))->name('privacy');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('gigs/create', GigsEdit::class)->name('gigs.create');
    Route::livewire('gigs/{gig}/edit', GigsEdit::class)->name('gigs.edit');

    Route::livewire('songs/create', SongsEdit::class)->name('songs.create');
    Route::livewire('songs/{song}/edit', SongsEdit::class)->name('songs.edit');

    Route::view('dashboard', 'pages.dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';
