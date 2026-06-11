<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'admin' && (int) $user->role !== 1) {
            return redirect()->route('dashboard');
        }

        if ($role === 'user' && (int) $user->role === 1) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}