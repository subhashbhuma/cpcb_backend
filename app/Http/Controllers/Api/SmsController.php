<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SmsController extends Controller
{
 public function __construct(protected SmsService $smsService)
    {
    }
    public function sendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'string', 'regex:/^(91)?[6-9][0-9]{9}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid mobile number.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $mobile = $request->input('mobile');
        $otp    = random_int(100000, 999999);

        try {
            $result = $this->smsService->sendOtp($mobile, $otp);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMS gateway is currently unreachable.',
            ], 502);
        }

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'SMS gateway rejected the request.',
                'gateway_code' => $result['code'],
                'raw' => $result['raw'],
            ], 502);
        }

        // TODO: store $otp against $mobile (cache/DB) with a 10-minute TTL
        // so you can verify it later, e.g.:
        // Cache::put("otp:{$mobile}", $otp, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'gateway_raw' => $result['raw'],
        ]);
    }

    /**
     * POST /api/sms/send
     * Body: { "mobile": "9999999999", "template": "otp", "vars": ["1234"] }
     *
     * Generic endpoint for sending any configured DLT template.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mobile'   => ['required', 'string', 'regex:/^(91)?[6-9][0-9]{9}$/'],
            'template' => ['required', 'string'],
            'vars'     => ['array'],
        ]);

        try {
            $result = $this->smsService->sendTemplate(
                $validated['mobile'],
                $validated['template'],
                $validated['vars'] ?? []
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => 'SMS gateway is currently unreachable.'], 502);
        }

        return response()->json([
            'success' => $result['success'],
            'gateway_code' => $result['code'],
            'gateway_raw'  => $result['raw'],
        ], $result['success'] ? 200 : 502);
    }
}
