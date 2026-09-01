<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LoginDetail;

class CheckConcurrentLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $currentSessionId = session()->getId();

        /*
        |--------------------------------------------------------------------------
        | Concurrent Login Check
        |--------------------------------------------------------------------------
        */
        if ($user->current_session_id !== $currentSessionId) {
            $this->logoutAndUpdate(
                $request,
                'forced_logout',
                'You have been logged out because your account was logged in from another device.',
                false // Do NOT clear DB session info for forced logout (keep the winner's ID)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Inactivity Timeout Check
        |--------------------------------------------------------------------------
        */
        $timeoutMinutes = (int) config(
            'session.inactivity_timeout',
            env('SESSION_INACTIVITY_TIMEOUT', 15)
        );

        $timeoutSeconds = $timeoutMinutes * 60;
        $lastActivity = $request->session()->get('last_activity');

        if ($lastActivity && (time() - $lastActivity) >= $timeoutSeconds) {
            $this->logoutAndUpdate(
                $request,
                'expired',
                'Session expired due to inactivity.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Activity Timestamp
        |--------------------------------------------------------------------------
        */
        if (!$request->routeIs('session.ping')) {
            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Central Logout Logic
    |--------------------------------------------------------------------------
    */
    protected function logoutAndUpdate(
        Request $request,
        string $status,
        string $message,
        bool $clearDB = true
    ) {
        $user = Auth::user();

        if ($user && $clearDB) {
            LoginDetail::where('session_id', session()->getId())
                ->whereNull('logout_at')
                ->update([
                    'logout_at' => now(),
                    'status'    => $status,
                ]);

            $user->clearSessionInfo();
        } elseif ($user && !$clearDB) {
            // For forced_logout, just update the log but DON'T clear current_session_id in users table
            LoginDetail::where('session_id', session()->getId())
                ->whereNull('logout_at')
                ->update([
                    'logout_at' => now(),
                    'status'    => $status,
                ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'logout' => true,
                'message' => $message,
                'redirect_url' => route('login'),
            ], 200);
        }

        redirect()->route('login')->with('error', $message)->send();
        exit;
    }
}