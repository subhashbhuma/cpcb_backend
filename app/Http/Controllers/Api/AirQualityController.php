<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AirQualityController extends Controller
{
    private const CAAQMS_TARGET_URL = 'https://airquality.cpcb.gov.in/caaqms/cpcb_new_marquee_data';
    private const CACHE_KEY = 'caaqms_live_air_quality_data';
    private const RETRY_THROTTLE_KEY = 'caaqms_upstream_last_attempt';

    /**
     * Get live CAAQMS air quality data for cities marquee and AQI display.
     * The CAAQMS upstream portal updates once every hour (e.g., "26 Nov 2026, 11:00 AM").
     *
     * Caching Strategy:
     * - If cached data is already from the CURRENT hour, return immediately from cache.
     * - Once a new hour begins, attempt to fetch the new hour's data from upstream.
     * - If upstream hasn't published the new hour yet or temporarily fails, throttle re-fetches
     *   by 2 minutes to prevent spamming while continuing to serve the previous hour's data.
     */
    public function getAirQualityData(Request $request)
    {
        $cached = Cache::get(self::CACHE_KEY);

        // 1. Check if we already have valid data for the current calendar hour
        if (!empty($cached) && !empty($cached['data'])) {
            $dateTimeStr = $cached['date_time'] ?? null;
            if ($dateTimeStr && $this->isFromCurrentHour($dateTimeStr)) {
                return response()->json($cached)
                    ->header('Cache-Control', 'public, max-age=120');
            }

            // If the hour changed, but we already tried upstream within the last 2 minutes,
            // don't spam upstream on every request if CAAQMS hasn't published the new hour yet.
            if (Cache::has(self::RETRY_THROTTLE_KEY)) {
                return response()->json($cached)
                    ->header('Cache-Control', 'public, max-age=60');
            }
        }

        // 2. Fetch fresh data from upstream CAAQMS
        Cache::put(self::RETRY_THROTTLE_KEY, true, 120); // Throttle retries to every 2 mins if needed

        try {
            $timestamp = now()->timestamp;
            $url = self::CAAQMS_TARGET_URL . '?_t=' . $timestamp;

            $response = Http::timeout(12)->get($url);

            if ($response->successful()) {
                $payload = $response->json();

                if (!empty($payload) && isset($payload['data']) && is_array($payload['data']) && count($payload['data']) > 0) {
                    $dateTimeStr = $payload['date_time'] ?? null;
                    $isCurrentHour = $dateTimeStr ? $this->isFromCurrentHour($dateTimeStr) : false;

                    // If upstream gave us the current hour's data, cache until the end of the hour (+ a buffer of 5 minutes)
                    // If upstream still gave older hour, cache for 5 minutes so we recheck soon
                    $cacheSeconds = $isCurrentHour ? $this->secondsUntilNextHourBuffer() : 300;

                    Cache::put(self::CACHE_KEY, $payload, $cacheSeconds);

                    return response()->json($payload)
                        ->header('Cache-Control', 'public, max-age=' . ($isCurrentHour ? 120 : 60));
                }
            }

            Log::warning('CAAQMS upstream returned non-successful status or empty data: ' . $response->status());
        } catch (\Throwable $e) {
            Log::error('Error fetching CAAQMS air quality data from upstream: ' . $e->getMessage());
        }

        // 3. Fallback to existing cache if upstream was unreachable
        if (!empty($cached) && !empty($cached['data'])) {
            return response()->json($cached);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to fetch air quality data from CAAQMS at this moment.',
            'date_time' => null,
            'data' => []
        ], 503);
    }

    /**
     * Check if the API date_time string (e.g. "26 Nov 2026, 11:00 AM") matches the current hour.
     */
    private function isFromCurrentHour(string $dateTimeStr): bool
    {
        try {
            // CAAQMS format is typically "d M Y, h:i A" or parseable by Carbon
            $apiTime = Carbon::parse($dateTimeStr);
            $now = Carbon::now();

            return $apiTime->isSameDay($now) && $apiTime->hour === $now->hour;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Calculate seconds remaining until the start of the next hour + 2 minute grace buffer.
     */
    private function secondsUntilNextHourBuffer(): int
    {
        $now = Carbon::now();
        $nextHour = $now->copy()->addHour()->startOfHour()->addMinutes(2);
        $diff = $now->diffInSeconds($nextHour, false);

        return $diff > 60 ? $diff : 300;
    }
}
