<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TranslatorService;

class TranslationController extends Controller
{
    protected $translator;

    public function __construct(TranslatorService $translator)
    {
        $this->translator = $translator;
    }

    // Single translate
    public function translate(Request $request)
    {
        $text = $request->input('text');
        try {
            $translated = $this->translator->translate($text);
            return response()->json([
                'success' => true,
                'translation' => $translated
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Translation failed'
            ], 500);
        }
    }
}