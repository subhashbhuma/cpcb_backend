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
use App\Models\LoginDetail;
use Illuminate\Support\Facades\Session;
class LoginController extends Controller
{
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

        // dd(Hash::make($password));
        //Attempt login
        if (!Auth::attempt(['email' => $request->email, 'password' => $password])) {

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

        //Successful login
        RateLimiter::clear($throttleKey);

        session()->regenerate(); //Prevent session fixation

        $user->resetLoginAttempts();

        //Concurrent Login Check
        if ($user->isLoggedInElsewhere()) {

            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            $url = route('logout.other-devices', [
                'user_id' => CustomHelper::encryptData($user->id)
            ]);
            return response()->json([
                'status' => 'concurrent_login',
                'csrf_token' => csrf_token(),
                'logout_url' => $url,
                'message' => 'You are already logged in from another device. Do you want to logout from the other session?',
            ]);
        }

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
            'redirect' => route('secure.dashboard')
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

    // public function logOutOtherDevices()
    // {
    //     $userId = request()->query('user_id');
    //     if (!$userId) {
    //         return redirect()->route('login')->withErrors(['error' => 'User ID is required']);
    //     }

    //     $id = CustomHelper::decryptData($userId);
    //     if ($id) {
    //         $user = User::where('id', $id)->first();
    //         if ($user) {
    //             $user->clearSessionInfo();
    //         }
    //     }

    //     Auth::logout();
    //     request()->session()->invalidate();
    //     request()->session()->regenerateToken();
    //     return redirect()->route('login');
    // }

    public function logOutOtherDevices()
    {
        $userId = request()->query('user_id');
        $id = CustomHelper::decryptData($userId);

        if (!$id) {
            abort(403, 'Invalid or tampered payload');
        }

        if ($id) {
            $user = User::find($id);
            if ($user) {
                $user->clearSessionInfo();
            }
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
