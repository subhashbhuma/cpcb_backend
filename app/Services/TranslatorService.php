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
            if (empty($text)) {
                return '';
            }

            // Pre-process honorific abbreviations that trigger API hallucinations
            $normalizedText = $this->preprocessText($text);

            $response = Http::timeout(10)
                ->withoutVerifying()
                ->retry(2, 100)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($this->url, [
                    "sourceText" => $normalizedText,
                    "sourceLangCode" => "auto",
                    "targetLangCode" => $target
                ]);

            $result = $response->json();
            return $result['translatedText'] ?? '';
        } catch (\Throwable $e) {
            Log::error('Translation Exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return '';
        }
    }

    /**
     * Preprocess input text to fix known abbreviations that cause translation engine hallucinations.
     */
    protected function preprocessText(string $text): string
    {
        // Replace 'Sh.' or 'Sh ' with 'Shri '
        $text = preg_replace('/(^|\s)Sh\.\s*/i', '$1Shri ', $text);
        
        // Replace 'Smt.' or 'Smt ' with 'Shrimati '
        $text = preg_replace('/(^|\s)Smt\.\s*/i', '$1Shrimati ', $text);

        return trim($text);
    }
}