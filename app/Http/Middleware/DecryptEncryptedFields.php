<?php

namespace App\Http\Middleware;

use App\Helpers\CustomHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptEncryptedFields
{
    /**
     * Fields to automatically decrypt if encrypted.
     */
    protected array $encryptedFields = [
        'description',
        'description_hi',
        'answer',
        'answer_hi',
        'content',
        'content_hi',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $input = $request->all();

            array_walk_recursive($input, function (&$value, $key) {
                if (in_array($key, $this->encryptedFields) && is_string($value) && !empty($value)) {
                    $decrypted = CustomHelper::decryptData($value);
                    if ($decrypted !== false && $decrypted !== null) {
                        $value = $decrypted;
                    }
                }
            });

            $request->replace($input);
        }

        return $next($request);
    }
}
