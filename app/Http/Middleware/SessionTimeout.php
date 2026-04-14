<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Automatically logs out the user if they've been idle for the configured timeout.
 * Add to the 'web' middleware group in app/Http/Kernel.php.
 */
class SessionTimeout
{
    /** Idle timeout in minutes. Matches SESSION_LIFETIME in .env */
    private const TIMEOUT_MINUTES = 120;

    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $lastActivity = session('last_activity_time');

        if ($lastActivity && now()->diffInMinutes($lastActivity) >= self::TIMEOUT_MINUTES) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session has expired. Please log in again.']);
        }

        session(['last_activity_time' => now()]);

        return $next($request);
    }
}
