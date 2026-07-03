<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function handleLogin(Request $request)
    {
        // If it's a GET request, show the login form
        if ($request->isMethod('get')) {
            return view('pages.auth.login');
        }

        // If it's a POST request, process the login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'cf-turnstile-response' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->has('remember');

        $response = Http::timeout(10)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => env('TURNSTILE_SECRETKEY'),
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            return back()
                ->withErrors(['captcha' => 'CAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Find user
        $user = DB::table('mlm_users')
            ->where(function($query) use ($username) {
                $query->where('user_name', $username)
                      ->orWhere('email', $username)
                      ->orWhere('phone', $username);
            })
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->first();

            // $userDetails = DB::table('mlm_users_details')->select('id', 'user_id', 'profile_image')->where('user_id', $user->id)->first();
            
            // $profileImage = asset('storage/' . $userDetails->profile_image);
            // dd($profileImage);

        if ($user && Hash::check($password, $user->password)) {
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->user_name);
            Session::put('track_id', $user->track_id);
            Session::put('first_name', $user->first_name);
            Session::put('last_name', $user->last_name);
            Session::put('email', $user->email);
            Session::put('phone', $user->phone);
            // Session::put('profile_iamge', 'http://127.0.0.1:8000/storage/profile-images/M42mbZR7lEvKzP49uEQNE46xu1QZrgaTcLQ1m8pH.png');
            Session::put('membership_type', $user->membership_type);
            Session::put('is_payout_active', $user->is_payout_active);
            Session::put('logged_in', true);

            return redirect()->route('dashboard')
                ->with('success', 'Welcome back, ' . $user->first_name . '!');
        }

        return back()
            ->withErrors(['username' => 'Invalid credentials or account is inactive.'])
            ->withInput($request->only('username'));
    }

   public function logout(Request $request)
{
    // Clear all session data
    Session::flush();
    
    // Invalidate the session and regenerate CSRF token for security
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect to login page with a success message
    return redirect()->route('login')->with('success', 'You have been logged out successfully.');
}
}