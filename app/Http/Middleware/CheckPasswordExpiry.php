<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $isExpired = $user->password_expires_at && now()->greaterThan($user->password_expires_at);
            
            if ($user->force_password_change || $isExpired) {
                if (!$request->is('secure/force-password-change*') && !$request->is('secure/logout')) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Password expired or change required.',
                            'redirect' => route('force-password-change')
                        ], 403);
                    }
                    
                    return redirect()->route('force-password-change')
                        ->with('error', 'Your password has expired or you are required to change it. Please update your password to continue.');
                }
            }
        }

        return $next($request);
    }
}
