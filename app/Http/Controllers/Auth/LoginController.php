<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Helpers\CustomHelper;
use App\Models\User;
use App\Models\Otp;
use App\Models\LoginDetail;
use App\Services\EmailService;
use App\Services\RateLimitService;
use App\Services\SMSService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected $rateLimitService;

    public function __construct(RateLimitService $rateLimitService)
    {
        $this->rateLimitService = $rateLimitService;
    }

    public function index()
    {
        CustomHelper::setEncryptionKey();
        return view('auth.login');
    }

    public function checkLogin(Request $request)
    {
        //Rate Limiting (5 attempts per minute per email+IP)
        $throttleKey = Str::lower($request->input('email', '')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return response()->json([
                'status' => 'error',
                'message' => "Too many login attempts. Please try again in {$seconds} seconds."
            ], 429);
        }

        //Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required',
            'captcha' => 'required|mky_captcha'
        ], [
            'captcha.required' => 'Please enter the CAPTCHA code.',
            'captcha.mky_captcha' => 'The CAPTCHA code is incorrect. Please try again.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $password = CustomHelper::decryptPassword($request->password);

        if ($password === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials provided.',
                'key' => CustomHelper::setEncryptionKey()
            ], 400);
        }

        //Get user first (for lockout check)
        $user = User::where(['email' => $request->email])->first();

        if (!$user) {
            RateLimiter::hit($throttleKey);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials provided.'
            ], 401);
        }

        //Check lockout
        if ($user->isLockedOut()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is temporarily locked. Please try later.'
            ], 403);
        }

        //Attempt credential check (without logging in)
        if (!password_verify($password, $user->password)) {

            $user->registerFailedLogin();
            RateLimiter::hit($throttleKey);

            // Log failed attempt
            LoginDetail::create([
                'user_id' => $user->id,
                'session_id' => null,
                'ip_address' => $request->ip(),
                'status' => 'failed',
                'login_at' => now(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials provided.'
            ], 401);
        }

        //Successful credential verification
        RateLimiter::clear($throttleKey);
        $user->resetLoginAttempts();

        $userRoles = $user->roles->pluck('name')->toArray();

        //Concurrent Login Check
        if ($user->isLoggedInElsewhere()) {
            // set user id in session
            session()->put('logged_out_user_id', $user->id);

            return response()->json([
                'status' => 'concurrent_login',
                'csrf_token' => csrf_token(),
                'logout_url' => route('logout.other-devices'),
                'message' => 'You are already logged in from another device. Do you want to logout from the other session?',
            ]);
        }

        // SUPERADMIN bypass: Skip OTP for SUPERADMIN role - direct login
        if (in_array('SUPERADMIN', $userRoles)) {
            Auth::login($user);
            session()->regenerate();

            //Store login history
            LoginDetail::create([
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'status' => 'success',
                'login_at' => now(),
            ]);

            //Update session info in users table
            $user->updateSessionInfo();

            return response()->json([
                'status' => 'success',
                'message' => 'You have successfully logged in!',
                'redirect' => route('secure.dashboard'),
                'key' => CustomHelper::setEncryptionKey()
            ]);
        }

        // For all other roles: OTP 2FA required

        // Check OTP generation rate limiting for backend
        $otpGenerationLimit = $this->rateLimitService->checkBackendOtpGenerationLimit($request, $user);
        if ($otpGenerationLimit['limited']) {
            return response()->json([
                'status' => 'error',
                'message' => $otpGenerationLimit['message']
            ], 429);
        }

        // Generate and send OTP
        $otpData = Otp::generateOtp($user->email, $user->mobile_number);
        if (!$otpData['success']) {
            return response()->json([
                'status' => 'error',
                'message' => $otpData['message']
            ], 429);
        }

        // Record OTP generation attempt for backend
        $this->rateLimitService->recordBackendOtpGenerationAttempt($request, $user);

        // Send OTP via email
        // EmailService::sendBackendOtpEmail($user->email, $otpData['otp']);

        // Send OTP via SMS
        // try {
        //     $smsService = new SMSService();
        //     $smsService->sendOtp($user->mobile_number, $otpData['otp']);
        // } catch (\Exception $e) {
        //     Log::error('Failed to send OTP SMS: ' . $e->getMessage());
        // }

        // Store user ID in session temporarily
        session(['temp_user_id' => $user->id]);

        return response()->json([
            'status' => 'otp_required',
            'message' => 'OTP has been sent to your registered email and mobile number.',
            'key' => CustomHelper::setEncryptionKey()
        ]);
    }

    /**
     * Verify OTP and complete login
     */
    public function verifyOtp(Request $request)
    {
        // Check rate limiting first
        $rateLimitCheck = $this->rateLimitService->isRateLimited($request);

        if ($rateLimitCheck['limited']) {
            return response()->json([
                'status' => 'error',
                'message' => $rateLimitCheck['message']
            ], 429);
        }

        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6'
        ], [
            'otp.required' => 'OTP is required.',
            'otp.size' => 'OTP must be 6 digits.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $tempUserId = session('temp_user_id');
        if (!$tempUserId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        $user = User::find($tempUserId);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found. Please login again.'
            ], 401);
        }

        // Verify OTP
        if (Otp::verifyOtp($request->otp, $user->email, $user->mobile_number)) {
            // Record successful attempt (clears rate limiting)
            $this->rateLimitService->recordSuccessfulAttempt($request);
            $this->rateLimitService->recordSuccessfullBackendSendAttempt($request);
            $this->rateLimitService->recordSuccessfullBackendReSendAttempt($request);

            $request->session()->regenerate();

            // Login the user
            Auth::login($user);

            // Store login history
            LoginDetail::create([
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'status' => 'success',
                'login_at' => now(),
            ]);

            // Update session information
            $user->updateSessionInfo();

            // Clear temporary session
            session()->forget('temp_user_id');

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful!',
                'redirect' => route('secure.dashboard')
            ]);
        } else {
            // Record failed attempt
            $failedAttemptResult = $this->rateLimitService->recordFailedAttempt($request);

            return response()->json([
                'status' => 'error',
                'message' => $failedAttemptResult['message']
            ], $failedAttemptResult['blocked'] ? 429 : 401);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $tempUserId = session('temp_user_id');
        if (!$tempUserId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        $user = User::find($tempUserId);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found. Please login again.'
            ], 401);
        }

        // Check OTP resend rate limiting for backend
        $otpResendLimit = $this->rateLimitService->checkBackendOtpResendLimit($request, $user);
        if ($otpResendLimit['limited']) {
            return response()->json([
                'status' => 'error',
                'message' => $otpResendLimit['message']
            ], 429);
        }

        // Generate and send new OTP
        $otpData = Otp::generateOtp($user->email, $user->mobile_number);
        if (!$otpData['success']) {
            return response()->json([
                'status' => 'error',
                'message' => $otpData['message']
            ], 429);
        }

        // Record OTP resend attempt for backend
        $this->rateLimitService->recordBackendOtpResendAttempt($request, $user);

        // Send OTP via email
        // EmailService::sendBackendOtpEmail($user->email, $otpData['otp']);

        // Send OTP via SMS
        // try {
        //     $smsService = new SMSService();
        //     $smsService->sendOtp($user->mobile_number, $otpData['otp']);
        // } catch (\Exception $e) {
        //     Log::error('Failed to send OTP SMS: ' . $e->getMessage());
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'New OTP has been sent to your email and mobile number.'
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            LoginDetail::where('session_id', session()->getId())
                ->whereNull('logout_at')
                ->update([
                    'logout_at' => now(),
                    'status' => 'success'
                ]);

            $user->clearSessionInfo();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }



    public function checkSession()
    {
        $user = auth()->user();
        if (!$user || $user->current_session_id != session()->getId()) {
            if ($user) {
                // Log the forced logout in authentication_log
                if (class_exists(\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog::class)) {
                    $log = new \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog();
                    $log->authenticatable_type = get_class($user);
                    $log->authenticatable_id = $user->id;
                    $log->ip_address = request()->ip();
                    $log->user_agent = request()->header('User-Agent');
                    $log->login_at = null;
                    $log->logout_at = now();
                    $log->login_successful = 0;
                    $log->save();
                }

                $user->clearSessionInfo();
            }

            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return response()->json([
                'status' => 'INACTIVE',
                'logout' => true,
                'redirect_url' => route('login'),
                'message' => 'Your session has expired or you have been logged out.'
            ]);
        } else {
            return response()->json(['status' => 'ACTIVE', 'logout' => false]);
        }
    }

    public function logOutOtherDevices()
    {
        $userId = session()->get('logged_out_user_id') ?? null;
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'User not found']);
        }

        $user = User::find($userId);
        if ($user) {
            $user->clearSessionInfo();
        }

        session()->forget('logged_out_user_id');

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
