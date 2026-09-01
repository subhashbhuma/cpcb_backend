<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Mail\SendOtpEmail;
use App\Mail\SendOtpEmailForDHTIApplication;
use App\Services\RateLimitService;
use App\Services\SMSService;
use App\DTO\EmailLogDto;
use App\DTO\SmsLogDto;
use App\Services\EmailLogService;
use App\Services\SmsLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    protected $rateLimitService;

    public function __construct(RateLimitService $rateLimitService)
    {
        $this->rateLimitService = $rateLimitService;
    }
    /**
     * Verify email otp
     */
    public function verifyEmailOtp(Request $request)
    {
        // Check rate limiting first
        $rateLimitCheck = $this->rateLimitService->isRateLimited($request);

        if ($rateLimitCheck['limited']) {
            return response()->json([
                'success' => false,
                'message' => $rateLimitCheck['message']
            ], 429); // Too Many Requests
        }

        // Define validation rules
        $rules = [
            'email' => 'required|email|max:255',
            'otp' => 'required|max:20',
        ];

        // Create a validator instance and validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Otp::verifyOtp($request->otp, $request->email, null)) {
            // Record successful attempt (clears rate limiting)
            $this->rateLimitService->recordSuccessfulAttempt($request);

            session()->put('email_otp_verified', true);
            return response()->json(['success' => true, 'message' => 'Email OTP verified.'], 200);
        } else {
            // Record failed attempt
            $failedAttemptResult = $this->rateLimitService->recordFailedAttempt($request);

            return response()->json([
                'success' => false,
                'message' => $failedAttemptResult['message'],
            ], $failedAttemptResult['blocked'] ? 429 : 400);
        }
    }

    /**
     * Verify mobile otp
     */
    public function verifyMobileOtp(Request $request)
    {
        // Check rate limiting first
        $rateLimitCheck = $this->rateLimitService->isRateLimited($request);

        if ($rateLimitCheck['limited']) {
            return response()->json([
                'success' => false,
                'message' => $rateLimitCheck['message']
            ], 429); // Too Many Requests
        }

        // Define validation rules
        $rules = [
            'mobile' => 'required|max:10',
            'otp' => 'required|max:20',
        ];

        // Create a validator instance and validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Otp::verifyOtp($request->otp, null, $request->mobile)) {
            // Record successful attempt (clears rate limiting)
            $this->rateLimitService->recordSuccessfulAttempt($request);

            session()->put('mobile_otp_verified', true);
            return response()->json(['success' => true, 'message' => 'Mobile OTP verified.'], 200);
        } else {
            // Record failed attempt
            $failedAttemptResult = $this->rateLimitService->recordFailedAttempt($request);

            return response()->json([
                'success' => false,
                'message' => $failedAttemptResult['message'],
            ], $failedAttemptResult['blocked'] ? 429 : 400);
        }
    }

    public function sendPublicEmailOtp(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->input('email');

        DB::beginTransaction();

        try {
            $otpData = Otp::generateOtp($email, null);

            if (!$otpData['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $otpData['message'] ?? 'Too many OTP requests. Please try again later.'
                ], 429);
            }

            $otp = $otpData['otp'];
            $expiresAt = $otpData['expires_at'];

            $isDummyOtp = filter_var(config('app.is_dummy_otp', false), FILTER_VALIDATE_BOOLEAN);
            if (!$isDummyOtp) {
                // Send email
                Mail::to($email)->send(new SendOtpEmail($otp));
            } else {
                Log::info("Dummy email OTP generated for $email: $otp");
            }

            // Log email using EmailLogService
            $emailLogService = new EmailLogService();
            $emailLogDto = new EmailLogDto(
                $email,
                'otp',
                'CPCB Verification OTP Code',
                'sent',
                null,
                null,
                ['otp' => $otp, 'expires_at' => $expiresAt->toDateTimeString()],
                now()
            );
            $emailLogService->create($emailLogDto);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Public Email OTP send error', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            try {
                $emailLogService = new EmailLogService();
                $emailLogDto = new EmailLogDto(
                    $email,
                    'otp',
                    'CPCB Verification OTP Code',
                    'failed',
                    null,
                    $e->getMessage(),
                    null,
                    now()
                );
                $emailLogService->create($emailLogDto);
            } catch (\Exception $logEx) {
                Log::error('Failed to log public email OTP exception: ' . $logEx->getMessage());
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ], 500);
        }
    }

    public function sendPublicMobileOtp(Request $request)
    {
        $rules = [
            'mobile' => 'required|digits:10',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mobile = $request->input('mobile');

        DB::beginTransaction();

        try {
            $otpData = Otp::generateOtp(null, $mobile);

            if (!$otpData['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $otpData['message'] ?? 'Too many OTP requests. Please try again later.'
                ], 429);
            }

            $otp = $otpData['otp'];
            $expiresAt = $otpData['expires_at'];

            $isDummyOtp = filter_var(config('app.is_dummy_otp', false), FILTER_VALIDATE_BOOLEAN);
            if (!$isDummyOtp) {
                // Send SMS using SMSService
                $smsService = new SMSService();
                $smsService->sendOtp($mobile, $otp);
            } else {
                Log::info("Dummy mobile OTP generated for $mobile: $otp");
            }

            // Log SMS using SmsLogService
            $smsLogService = new SmsLogService();
            $smsLogDto = new SmsLogDto(
                $mobile,
                'otp',
                'CPCB Verification OTP Code',
                'sent',
                null,
                null,
                ['otp' => $otp, 'expires_at' => $expiresAt->toDateTimeString()],
                now()
            );
            $smsLogService->create($smsLogDto);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Public Mobile OTP send error', [
                'mobile' => $mobile,
                'error' => $e->getMessage(),
            ]);

            try {
                $smsLogService = new SmsLogService();
                $smsLogDto = new SmsLogDto(
                    $mobile,
                    'otp',
                    'CPCB Verification OTP Code',
                    'failed',
                    null,
                    $e->getMessage(),
                    null,
                    now()
                );
                $smsLogService->create($smsLogDto);
            } catch (\Exception $logEx) {
                Log::error('Failed to log public mobile OTP exception: ' . $logEx->getMessage());
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ], 500);
        }
    }

    public function verifyPublicEmailOtp(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
            'otp' => 'required|string|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->input('email');
        $otp = $request->input('otp');

        if (Otp::verifyOtp($otp, $email, null)) {
            \Illuminate\Support\Facades\Cache::put("otp_verified_email_{$email}", true, now()->addMinutes(15));

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }
    }

    public function verifyPublicMobileOtp(Request $request)
    {
        $rules = [
            'mobile' => 'required|digits:10',
            'otp' => 'required|string|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mobile = $request->input('mobile');
        $otp = $request->input('otp');

        if (Otp::verifyOtp($otp, null, $mobile)) {
            \Illuminate\Support\Facades\Cache::put("otp_verified_phone_{$mobile}", true, now()->addMinutes(15));

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }
    }
}
