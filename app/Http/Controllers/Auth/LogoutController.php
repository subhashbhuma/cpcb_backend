<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginDetail;

class LogoutController extends Controller
{
    public function index()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
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


            if (class_exists(\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog::class)) {
                $log = new \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog();
                $log->authenticatable_type = get_class($user);
                $log->authenticatable_id = $user->id;
                $log->ip_address = $request->ip();
                $log->user_agent = $request->header('User-Agent');
                $log->login_at = null;
                $log->logout_at = now();
                $log->login_successful = 0; // Distinct Logout Event Marker
                $log->save();
            }

            $user->clearSessionInfo();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
