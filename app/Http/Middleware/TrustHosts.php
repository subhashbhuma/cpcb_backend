<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    public function hosts(): array
    {
        $hosts = [];

        if ($appUrl = config('app.url')) {
            $host = parse_url($appUrl, PHP_URL_HOST);

            if ($host) {
                $hosts[] = '^' . preg_quote($host) . '$';
            }
        }

        $hosts[] = '^127\.0\.0\.1$';
        $hosts[] = '^localhost$';

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
            $allowedHosts = config('app.allowed_hosts', []);

            // 1. Add APP_URL host automatically
            if ($appUrl = config('app.url')) {
                $parsed = parse_url($appUrl);
                $host = $parsed['host'] ?? '';
                $port = $parsed['port'] ?? '';
                if ($host) {
                    $allowedHosts[] = $host;
                    if ($port) {
                        $allowedHosts[] = $host . ':' . $port;
                    }
                }
            }

            if (!in_array($hostHeader, $allowedHosts)) {
                abort(400, 'Bad Request');
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