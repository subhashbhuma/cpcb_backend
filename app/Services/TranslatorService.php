<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class TranslatorService
{
    protected $url;

    public function __construct()
    {
        $this->url = config('app.translation.url');
    }

    public function translate($text, $target = 'hin')
    {
        try {
        if (empty($text))
            return '';

        $response = Http::timeout(10)
            ->withoutVerifying()
            ->retry(2, 100)
            ->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post($this->url, [
                "sourceText" => $text,
                "sourceLangCode" => "auto",
                "targetLangCode" => $target
            ]);

        $result = $response->json();
        return $result['translatedText'] ?? '';
        }catch (\Throwable $e) {
            Log::error('Translation Exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return '';
        }
    }
}