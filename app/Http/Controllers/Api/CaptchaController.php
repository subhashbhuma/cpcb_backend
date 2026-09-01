<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CaptchaController extends Controller
{
    /**
     * Generate a new captcha instance for the API.
     */
    public function getCaptcha()
    {
        $captchaData = app('mky-captcha')->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'image' => $captchaData['image'],
                'audio' => $captchaData['audio'],
                'key'   => $captchaData['key'] ?? null // Use this if the package provides a unique key
            ]
        ]);
    }

    /**
     * Verify the captcha input.
     */
    public function verifyCaptcha(Request $request)
    {
        $userInput = $request->input('captcha');

        // Manual check using the package's internal logic
        $isCheckPassed = app('mky-captcha')->check($userInput);

        if (!$isCheckPassed) {
            // Debugging: Let's see what is in the session vs what was sent
            return response()->json([
                'success' => false,
                'message' => 'Manual verification failed.',
                'debug' => [
                    'sent_value' => $userInput,
                    'session_id' => session()->getId(),
                    // 'actual_answer' => session()->get('mky-captcha.key'), // Only if you want to see the real answer for debugging
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verified manually!'
        ]);
    }




}
