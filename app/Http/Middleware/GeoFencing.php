<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeoFenceAlertMail;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class GeoFencing
{
    /**
     * Allowed country codes (comma-separated in .env)
     */
    protected array $allowedCountries;

    /**
     * Whitelisted IPs that bypass geo-fencing (comma-separated in .env)
     */
    protected array $whitelistedIps;

    /**
     * Whether geo-fencing is enabled
     */
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled = filter_var(env('GEO_FENCE_ENABLED', true), FILTER_VALIDATE_BOOLEAN);

        $this->allowedCountries = array_map(
            'trim',
            explode(',', env('GEO_FENCE_ALLOWED_COUNTRIES', 'IN'))
        );

        $this->whitelistedIps = array_map(
            'trim',
            explode(',', env('GEO_FENCE_WHITELISTED_IPS', '127.0.0.1,::1'))
        );
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if geo-fencing is disabled
        if (!$this->enabled) {
            return $next($request);
        }

        $clientIp = $this->getClientIp($request);

        // TEST MODE: Force a fake country for local testing
        // Set GEO_FENCE_TEST_COUNTRY=US in .env to simulate a blocked country
        // Remove this variable or leave it empty to use real IP lookup
        $testCountry = env('GEO_FENCE_TEST_COUNTRY', '');
        if (!empty($testCountry)) {
            $countryCode = strtoupper(trim($testCountry));
        } else {
            // Skip for private/localhost IPs (development)
            if ($this->isPrivateIp($clientIp)) {
                return $next($request);
            }

            // Skip for whitelisted IPs
            if (in_array($clientIp, $this->whitelistedIps)) {
                return $next($request);
            }

            // Get country code for the IP (cached for 24 hours)
            $countryCode = $this->getCountryCode($clientIp);
        }

        // If we couldn't determine the country, allow access but log a warning
        if ($countryCode === null) {
            Log::warning('[GeoFence] Could not determine country for IP', [
                'ip' => $clientIp,
                'uri' => $request->getRequestUri(),
                'user_agent' => $request->userAgent(),
            ]);
            return $next($request);
        }

        // Check if the country is allowed
        if (!in_array(strtoupper($countryCode), $this->allowedCountries)) {
            // Log the blocked attempt as an alert
            $alertDetails = [
                'ip' => $clientIp,
                'country' => $countryCode,
                'uri' => $request->getRequestUri(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ];

            Log::channel('single')->alert('[GeoFence] BLOCKED — Access attempt from outside allowed region', $alertDetails);

            // Send email alert to configured email address
            try {
                $alertEmails = env('GEO_FENCE_ALERT_EMAIL');
                if (!empty($alertEmails)) {
                    // Support comma-separated multiple emails
                    $emails = array_map('trim', explode(',', $alertEmails));
                    Mail::to($emails)->send(new GeoFenceAlertMail($alertDetails));
                }
            } catch (\Exception $e) {
                Log::error('[GeoFence] Failed to send alert email: ' . $e->getMessage());
            }

            // Return 403 — JSON for AJAX, view for page requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not authorized to access this resource.',
                ], 403);
            }

            abort(403, 'Access denied. You are not authorized to access this resource.');
        }

        return $next($request);
    }

    /**
     * Get country code for an IP address (cached 24 hours)
     */
    protected function getCountryCode(string $ip): ?string
    {
        $cacheKey = 'geo_fence_country_' . md5($ip);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            try {
                $response = Http::timeout(5)
                    ->get("http://ip-api.com/json/{$ip}", [
                        'fields' => 'status,countryCode',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['status']) && $data['status'] === 'success') {
                        return $data['countryCode'] ?? null;
                    }
                }

                return null;
            } catch (\Exception $e) {
                Log::error('[GeoFence] IP geolocation API error', [
                    'ip' => $ip,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    /**
     * Get the real client IP address (supports proxies and CDNs)
     */
    protected function getClientIp(Request $request): string
    {
        // Check headers in priority order
        $headers = [
            'HTTP_CF_CONNECTING_IP',   // Cloudflare
            'HTTP_X_REAL_IP',          // Nginx reverse proxy
            'HTTP_X_FORWARDED_FOR',    // Load balancer / proxy
            'HTTP_CLIENT_IP',          // Proxy
        ];

        foreach ($headers as $header) {
            $ip = $request->server($header);

            if ($ip && $ip !== 'unknown') {
                // X-Forwarded-For may contain multiple IPs — take the first
                if (str_contains($ip, ',')) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $request->ip();
    }

    /**
     * Check if the IP is a private/reserved address (localhost, LAN, etc.)
     */
    protected function isPrivateIp(string $ip): bool
    {
        // IPv6 loopback
        if ($ip === '::1') {
            return true;
        }

        // Filter returns false for private/reserved IPs
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}
