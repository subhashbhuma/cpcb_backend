<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $ttl  Cache time-to-live in seconds (default 300 seconds / 5 minutes)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $ttl = 300)
    {
        // Only cache GET requests or read-only public datatable/fetch POST queries
        $isGet = $request->method() === 'GET';
        $isReadOnlyPost = $request->method() === 'POST' && (
            str_contains($request->path(), 'datatable') ||
            str_contains($request->path(), 'fetchAqiList')
        );

        if (!$isGet && !$isReadOnlyPost) {
            return $next($request);
        }

        // List of endpoints to completely exclude from cache
        $excludePaths = [
            'captcha',
            'visitor/add',
            'visitor/stats',
            'search/suggestions',
            'today-entries',
            'submit',
            'otp',
            'login',
        ];

        foreach ($excludePaths as $path) {
            if ($request->is("api/{$path}*") || $request->is("*/{$path}*")) {
                return $next($request);
            }
        }

        // Generate cache key based on URL, language, and request payload
        $language = $request->header('language', 'en');
        $payload = $isReadOnlyPost ? json_encode($request->all()) : '';
        $cacheKey = 'api_response_' . md5($request->fullUrl() . '_' . $language . '_' . $payload);

        // Serve from cache if it exists
        $cachedData = Cache::get($cacheKey);
        if ($cachedData !== null) {
            return response()->json(
                $cachedData['content'],
                $cachedData['status'],
                $cachedData['headers']
            );
        }

        // Process request
        $response = $next($request);

        // Only cache successful JSON responses
        if ($response->isSuccessful() && method_exists($response, 'getContent')) {
            $contentType = $response->headers->get('Content-Type');
            if ($contentType && str_contains(strtolower($contentType), 'application/json')) {
                $content = json_decode($response->getContent(), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $status = $response->getStatusCode();
                    
                    // Filter headers to avoid caching cookie/session cookies or temporary caching headers
                    $headers = [];
                    foreach ($response->headers->all() as $key => $values) {
                        if (!in_array(strtolower($key), ['set-cookie', 'cookie', 'date', 'expires', 'cache-control', 'pragma'])) {
                            $headers[$key] = $values;
                        }
                    }

                    Cache::put($cacheKey, [
                        'content' => $content,
                        'status' => $status,
                        'headers' => $headers,
                    ], (int)$ttl);
                }
            }
        }

        return $response;
    }
}
