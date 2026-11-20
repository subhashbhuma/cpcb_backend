<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    public function hosts(): array
    {
        $hosts = [
            '^127\.0\.0\.1$',
            '^localhost$',
        ];

        // 1. Add host from APP_URL
        if ($appUrl = config('app.url')) {
            $host = parse_url($appUrl, PHP_URL_HOST);
            if ($host) {
                $hosts[] = '^' . preg_quote($host) . '$';
            }
        }

        // 2. Add all hosts from ALLOWED_HOSTS (.env)
        $allowedHosts = config('app.allowed_hosts', []);
        foreach ($allowedHosts as $entry) {
            // Normalize: strip scheme (http://, https://) and path/trailing slash
            $parsed = parse_url($entry, PHP_URL_HOST);
            if (!$parsed) {
                // If no scheme was present (e.g. "45.127.74.242" or "example.com:8000")
                $clean = preg_replace('#^https?://#', '', trim($entry));
                $clean = explode('/', $clean)[0];
                $clean = explode(':', $clean)[0];
            } else {
                $clean = $parsed;
            }

            if (!empty($clean)) {
                $pattern = '^' . preg_quote($clean) . '$';
                if (!in_array($pattern, $hosts, true)) {
                    $hosts[] = $pattern;
                }
            }
        }

        return $hosts;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(\Illuminate\Http\Request $request, $next)
    {
        $hostHeader = $request->header('Host');

        if ($hostHeader) {
            $hostWithoutPort = explode(':', $hostHeader)[0];
            $allowedPatterns = $this->hosts();

            $isAllowed = false;
            foreach ($allowedPatterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $hostWithoutPort)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                abort(400, 'Bad Request: Host not allowed');
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Determine if the application should specify trusted hosts.
     *
     * @return bool
     */
    protected function shouldSpecifyTrustedHosts()
    {
        return true;
    }
}
