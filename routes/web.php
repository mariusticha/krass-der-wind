<?php

use App\Livewire\Pages\Gigs\Index as GigsIndex;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auto-login for local development
Route::get('/login', function () {
    if (request()->has('auto_login') && app()->environment('local')) {
        $user = User::first();
        if ($user) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }
    }
    return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->create(request());
})->name('login');

Route::livewire('gigs', GigsIndex::class)->name('gigs.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/settings.php';
