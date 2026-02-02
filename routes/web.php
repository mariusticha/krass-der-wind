<?php

use App\Livewire\Pages\Gigs\Edit as GigsEdit;
use App\Livewire\Pages\Gigs\Index as GigsIndex;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome-2')->name('home');

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

Route::livewire('gigs', GigsIndex::class)->name('gigs.index');
Route::livewire('gigs/create', GigsEdit::class)->middleware('auth')->name('gigs.create');
Route::livewire('gigs/{gig}/edit', GigsEdit::class)->middleware('auth')->name('gigs.edit');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/settings.php';
