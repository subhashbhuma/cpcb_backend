<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\Response;

class PurifyInputMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass for specific routes (Social Media & Video Gallery) that handle embed codes or raw scripts.
        if ($request->is('secure/social-medias*') || $request->is('secure/video-galleries*')) {
            return $next($request);
        }

        $input = $request->all();

        // Fields that contain HTML (CKEditor) and should be cleaned but not stripped
        $htmlFields = [
            'description',
            'description_hi',
            'content',
            'content_hi',
            'message',
            'remarks',
            'answer',
            'answer_hi',
            'site_address',
            'site_address_hi',
            'disclaimer',
            'disclaimer_hi',
        ];

        // Specific fields to skip purification and stripping (mostly for embed codes)
        $skipFields = [
            'embed_code',
            'youtube_embed_code',
        ];

        array_walk_recursive($input, function (&$value, $key) use ($htmlFields, $skipFields) {
            if (is_string($value)) {
                if (in_array($key, $skipFields)) {
                    // Do nothing, bypass purification/stripping
                } elseif (in_array($key, $htmlFields)) {
                    // Clean HTML but allow safe tags
                    $value = Purifier::clean($value);
                } else {
                    // Remove all HTML from normal inputs
                    $value = strip_tags($value);
                }
            }
        });

        $request->replace($input);

        return $next($request);
    }
}