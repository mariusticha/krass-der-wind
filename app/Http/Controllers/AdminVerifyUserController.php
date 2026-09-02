<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class AdminVerifyUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user): RedirectResponse
    {
        if ($user->hasAdminVerifiedAccess()) {
            return redirect()->route('settings.users')->with('status', 'user-already-verified');
        }

        $user->markAsAdminVerified();

        return redirect()->route('settings.users')->with('status', 'user-verified');
    }
}
