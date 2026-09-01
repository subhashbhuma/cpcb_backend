<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateReferer
{
    protected static ?array $cachedReferers = null;

    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $referer = $request->headers->get('referer');

            if ($referer && !$this->isValidReferer($referer)) {
                abort(403, 'Invalid Referer');
            }
        }

        return $next($request);
    }

    protected function isValidReferer(string $referer): bool
    {
        if (static::$cachedReferers === null) {
            static::$cachedReferers = config('app.allowed_referers', []);
        }

        foreach (static::$cachedReferers as $allowed) {
            if (str_starts_with($referer, $allowed)) {
                return true;
            }
        }

        return false;
    }
}
