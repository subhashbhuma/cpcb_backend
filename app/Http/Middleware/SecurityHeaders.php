<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /*
        |--------------------------------------------------------------------------
        | Remove Technology Disclosure
        |--------------------------------------------------------------------------
        */
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        /*
        |--------------------------------------------------------------------------
        | HTTP Strict Transport Security (HSTS)
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload'
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent Clickjacking
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'X-Frame-Options',
            'SAMEORIGIN'
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent MIME Type Sniffing
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        /*
        |--------------------------------------------------------------------------
        | XSS Protection
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'X-XSS-Protection',
            '1; mode=block'
        );

        /*
        |--------------------------------------------------------------------------
        | Referrer Policy
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        /*
        |--------------------------------------------------------------------------
        | Permissions Policy
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(), usb=()'
        );

        if (!$request->is('api/*') && !$request->expectsJson()) {
            /*
            |--------------------------------------------------------------------------
            | Cache Control
            |--------------------------------------------------------------------------
            */
            $response->headers->set(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0'
            );

            $response->headers->set(
                'Pragma',
                'no-cache'
            );

            $response->headers->set(
                'Expires',
                '0'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cross Origin Policies
        |--------------------------------------------------------------------------
        */
        $response->headers->set(
            'Cross-Origin-Opener-Policy',
            'same-origin'
        );

        $response->headers->set(
            'Cross-Origin-Resource-Policy',
            $request->is('api/public-files') ? 'cross-origin' : 'same-origin'
        );

        /*
        |--------------------------------------------------------------------------
        | Remove Insecure Headers
        |--------------------------------------------------------------------------
        */
        $response->headers->remove('X-AspNet-Version');
        $response->headers->remove('X-AspNetMvc-Version');

        return $response;
    }
}
