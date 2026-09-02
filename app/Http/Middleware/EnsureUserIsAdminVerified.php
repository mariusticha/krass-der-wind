<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        if ($user->can('admin') || $user->hasAdminVerifiedAccess()) {
            return $next($request);
        }

        if ($request->routeIs('approval.notice')) {
            return $next($request);
        }

        return redirect()->route('approval.notice');
    }
}
