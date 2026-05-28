<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Treat null/empty role as 'admin' for backwards compatibility
        // (existing users before role migration)
        $userRole = $user->role ?: 'admin';

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect to the correct portal based on actual role
        if ($user->isHrd()) {
            return redirect()->route('hrd.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        if ($user->isAdmin() || $userRole === 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Unknown role — log out to prevent loop
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('error', 'Role tidak dikenali. Silakan hubungi administrator.');
    }
}
