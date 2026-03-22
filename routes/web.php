<?php

use App\Livewire\Pages\Gigs\Edit as GigsEdit;
use App\Livewire\Pages\Gigs\Index as GigsIndex;
use App\Livewire\Pages\Parts\Index as PartsIndex;
use App\Livewire\Pages\Parts\Edit as PartsEdit;
use App\Livewire\Pages\Sheets\Index as SheetsIndex;
use App\Livewire\Pages\Sheets\Edit as SheetsEdit;
use App\Livewire\Pages\Songs\Index as SongsIndex;
use App\Livewire\Pages\Songs\Edit as SongsEdit;
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

Route::group([], function () {
    /* ----- navigation ----- */
    Route::view('/', 'pages.welcome')->name('home');
    Route::livewire('gigs', GigsIndex::class)->name('gigs.index');
    Route::livewire('songs', SongsIndex::class)->name('songs.index');

    /* ----- user menu ----- */
    Route::livewire('parts', PartsIndex::class)->name('parts.index');
    Route::livewire('sheets', SheetsIndex::class)->name('sheets.index');

    /* ----- footer ----- */
    Route::view('imprint', 'pages.imprint', config('app.legals'))->name('imprint');
    Route::view('privacy', 'pages.privacy', config('app.legals'))->name('privacy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    /* ----- dashboard ----- */
    Route::view('dashboard', 'pages.dashboard')->name('dashboard');

    /* ----- crud ----- */
    Route::livewire('gigs/create', GigsEdit::class)->name('gigs.create');
    Route::livewire('gigs/{gig}/edit', GigsEdit::class)->name('gigs.edit');

    Route::livewire('songs/create', SongsEdit::class)->name('songs.create');
    Route::livewire('songs/{song}/edit', SongsEdit::class)->name('songs.edit');

    Route::livewire('parts/create', PartsEdit::class)->middleware('auth')->name('parts.create');
    Route::livewire('parts/{part}/edit', PartsEdit::class)->middleware('auth')->name('parts.edit');

    Route::livewire('sheets/create', SheetsEdit::class)->middleware('auth')->name('sheets.create');
    Route::livewire('sheets/{sheet}/edit', SheetsEdit::class)->middleware('auth')->name('sheets.edit');
});

require __DIR__ . '/settings.php';
