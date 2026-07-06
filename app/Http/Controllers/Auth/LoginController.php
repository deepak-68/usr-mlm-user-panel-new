<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }


    public function handleLogin(Request $request)
    {
        // dd(Session::all());
        if ($request->isMethod('get')) {
            return view('pages.auth.login');
        }

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'cf-turnstile-response' => 'required',
        ]);

        $captcha = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret' => env('TURNSTILE_SECRETKEY'),
                'response' => $request->input('cf-turnstile-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (!($captcha->json()['success'] ?? false)) {
            return back()
                ->withErrors(['captcha' => 'CAPTCHA verification failed.'])
                ->withInput();
        }

        $response = Http::post($this->apiBaseUrl.'/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        // dd($response->json()); // Debugging line to inspect the response

        if (!$response->successful()) {
            return back()
                ->withErrors([
                    'username' => 'Invalid credentials.'
                ])
                ->withInput($request->only('username'));
        }

        $login = $response->json();

        Session::put('token', $login['token']);

        if (isset($login['user'])) {

            Session::put('user', $login['user']);

        } else {
            $me = Http::withToken($login['token'])
                ->get(config('services.api.url').'/api/me');

            if (!$me->successful()) {
                Session::forget('token');

                return back()->withErrors([
                    'username' => 'Unable to fetch user details.'
                ]);
            }

            Session::put('user', $me->json()['user']);
        }

        Session::put('logged_in', true);

        return redirect()->route('dashboard');
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