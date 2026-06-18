<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BackOfficeMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        $allowedRoles = empty($roles) ? ['admin', 'editor', 'hr'] : $roles;

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
