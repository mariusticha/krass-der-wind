<?php

use App\Http\Controllers\AdminVerifyUserController;
use App\Http\Controllers\SheetFileController;
use App\Livewire\Pages\Gigs\Edit as GigsEdit;
use App\Livewire\Pages\Gigs\Index as GigsIndex;
use App\Livewire\Pages\Parts\Edit as PartsEdit;
use App\Livewire\Pages\Parts\Index as PartsIndex;
use App\Livewire\Pages\Songs\Edit as SongsEdit;
use App\Livewire\Pages\Songs\Index as SongsIndex;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auto-login for local development
Route::get('/login', function () {
    if (request()->has('auto_login') && app()->environment('local')) {
        $user = User::query()->first();
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


    /* ----- footer ----- */
    Route::view('imprint', 'pages.imprint', config('app.legals'))->name('imprint');
    Route::view('privacy', 'pages.privacy', config('app.legals'))->name('privacy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pending-approval', function () {
        $user = request()->user();

        if ($user->can('admin') || $user->hasAdminVerifiedAccess()) {
            return redirect()->route('dashboard');
        }

        return view('auth.pending-approval');
    })->name('approval.notice');

    Route::get('admin/users/{user}/verify', AdminVerifyUserController::class)
        ->middleware(['can:admin', 'signed'])
        ->name('admin.users.verify');
});

Route::middleware(['auth', 'verified', 'admin_verified'])->group(function () {
    /* ----- user menu ----- */
    Route::view('dashboard', 'pages.dashboard')->name('dashboard');
    Route::livewire('parts', PartsIndex::class)->name('parts.index');

    /* ----- crud ----- */
    Route::livewire('gigs/create', GigsEdit::class)->name('gigs.create');
    Route::livewire('gigs/{gig}/edit', GigsEdit::class)->name('gigs.edit');

    Route::livewire('songs/create', SongsEdit::class)->name('songs.create');
    Route::livewire('songs/{song}/edit', SongsEdit::class)->name('songs.edit');

    Route::livewire('parts/create', PartsEdit::class)->name('parts.create');
    Route::livewire('parts/{part}/edit', PartsEdit::class)->name('parts.edit');

    /* ----- sheet file download (signed) ----- */
    Route::get('sheets/{sheet}/file', [SheetFileController::class, 'show'])
        ->middleware('signed')
        ->name('sheets.file');
});

require __DIR__ . '/settings.php';
