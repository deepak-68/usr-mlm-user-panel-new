<?php

namespace App\Http\Controllers;

use App\Models\MlmUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.api.base_url');
    }

     
    public function editProfile()
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()
                ->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(10)
                ->get($this->apiBaseUrl . '/profile');

            if ($response->unauthorized()) {
                Session::flush();

                return redirect()
                    ->route('login')
                    ->with('error', 'Your session has expired. Please log in again.');
            }

            if ($response->failed()) {
                Log::error('Profile API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Unable to load your profile at the moment.');
            }

            $profile = $response->json();

            if (!isset($profile['data'])) {
                Log::error('Profile API returned invalid data', [
                    'response' => $profile,
                ]);

                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Invalid response from server.');
            }

            return view('pages.edit-my-profile', [
                'user' => $profile['data'],
                'data' => $profile,
            ]);

        } catch (\Exception $e) {

            Log::error('Error while fetching profile', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('dashboard')
                ->with('error', 'Something went wrong while loading your profile.');
        }
    }

    // Profile update
    public function updateProfile(Request $request)
    {
        $userId = Session::get('user.id');
        $token = Session::get('token');

        if (!$userId) {
            return redirect()->route('login');
        } 

         $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
        ]);

        try {
            $response = Http::timeout(10)->withToken($token)->post(
                $this->apiBaseUrl . '/profile/update',
                [
                    'user_id'    => $userId,
                    'first_name' => $validated['first_name'],
                    'last_name'  => $validated['last_name'],
                    'phone'      => $validated['phone'],
                ]
            );

            if (!$response->successful()) {
                Log::error('Profile update API failed', [
                    'user_id' => $userId,
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'Unable to update profile. Please try again.');
            }

            $result = $response->json();

            if (isset($result['status']) && $result['status'] === false) {
                return back()
                    ->withInput()
                    ->with('error', $result['message'] ?? 'Profile update failed.');
            }

            return back()->with(
                'success',
                $result['message'] ?? 'Profile updated successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('Profile update exception', [
                'user_id' => $userId,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again later.');
        }

    }

    // Profile image edit page
    public function editProfileImage()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = MlmUser::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        return view('pages.profile-image-edit', compact('user'));
    }

    // Profile image upload
    public function uploadProfileImage(Request $request)
    {
        $userId = Session::get('user.id') ?? Session::get('user_id');

        if (!$userId) {
            return response()->json(['status' => false, 'message' => 'Please login first.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid file.'], 422);
        }

        try {
            $file = $request->file('profile_image');
            $response = Http::withToken(Session::get('token'))
                ->timeout(30)
                ->asMultipart()
                ->attach('profile_image', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
                ->post($this->apiBaseUrl . '/profile/update-image', [
                    'user_id' => $userId,
                ]);

            if ($response->unauthorized()) {
                Session::flush();
                return response()->json(['status' => false, 'message' => 'Session expired. Please login again.'], 401);
            }

            if ($response->successful()) {
                $body = $response->json();

                // Refresh session user data from API
                try {
                    $profileRes = Http::withToken(Session::get('token'))
                        ->acceptJson()
                        ->timeout(10)
                        ->get($this->apiBaseUrl . '/profile');

                    if ($profileRes->successful()) {
                        $profileData = $profileRes->json('data');
                        if ($profileData) {
                            Session::put('user', $profileData);
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to refresh session after image upload', ['error' => $e->getMessage()]);
                }

                return response()->json([
                    'status' => true,
                    'message' => $body['message'] ?? 'Profile image updated successfully!',
                    'image_url' => $body['image_url'] ?? null,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile image.',
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Profile image upload exception', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
      public function showChangePasswordForm()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = MlmUser::find($userId);

        // if (!$user) {
        //     return redirect()->route('login')->with('error', 'User not found.');
        // }

        return view('pages.change-password', compact('user'));
    }

    // Password change karna
    public function changePassword(Request $request)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $validated = $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        try {

            $response = Http::timeout(10)
                ->post(
                    $this->apiBaseUrl . '/change-password',
                    [
                        'user_id' => $userId,
                        'old_password' => $validated['old_password'],
                        'new_password' => $validated['new_password'],
                        'new_password_confirmation' => $request->new_password_confirmation,
                    ]
                );

            $result = $response->json();

            if (!$response->successful()) {

                Log::error('Change password API failed', [
                    'user_id' => $userId,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        $result['message'] ?? 'Unable to change password.'
                    );
            }

            return back()->with(
                'success',
                $result['message'] ?? 'Password changed successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('Change password exception', [
                'user_id' => $userId,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Something went wrong. Please try again later.'
                );
        }
    }
      // Change Transaction Password Form
    public function showChangeTransactionPasswordForm()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = MlmUser::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        return view('pages.change-transaction-password', compact('user'));
    }

    // Change Transaction Password
    public function changeTransactionPassword(Request $request)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = MlmUser::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'old_transaction_password' => 'required|string',
            'new_transaction_password' => 'required|string|min:6|confirmed',
        ], [
            'old_transaction_password.required' => 'Old transaction password is required.',
            'new_transaction_password.required' => 'New transaction password is required.',
            'new_transaction_password.min' => 'New transaction password must be at least 6 characters.',
            'new_transaction_password.confirmed' => 'New transaction password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Old transaction password check (agar set hai toh)
        if ($user->transaction_password) {
            if (!Hash::check($request->old_transaction_password, $user->transaction_password)) {
                return back()->withErrors(['old_transaction_password' => 'Old transaction password is incorrect.'])
                    ->withInput();
            }
        } else {
            // Agar pehle se transaction password nahi hai, toh old password login password se match karo
            if (!Hash::check($request->old_transaction_password, $user->password)) {
                return back()->withErrors(['old_transaction_password' => 'Old transaction password is incorrect.'])
                    ->withInput();
            }
        }

        // New password same toh nahi hai na?
        if (Hash::check($request->new_transaction_password, $user->transaction_password ?? '')) {
            return back()->withErrors(['new_transaction_password' => 'New transaction password must be different from old password.'])
                ->withInput();
        }

        // Transaction password update karo
        $user->update([
            'transaction_password' => Hash::make($request->new_transaction_password),
        ]);

        return redirect()->route('user.change-transaction-password')
            ->with('success', 'Transaction password changed successfully!');
    }

    // Forgot Transaction Password Form
   public function showForgotTransactionPasswordForm()
{
    $userId = Session::get('user_id');

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $user = MlmUser::find($userId);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

   
    return view('pages.forgot-transaction-password', compact('user'));
}

    // Forgot Transaction Password Submit
    public function forgotTransactionPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // User dhundo
        $user = MlmUser::where('user_name', $request->username)
            ->where('first_name', 'like', '%' . $request->name . '%')
            ->where('email', $request->email)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->first();

        if (!$user) {
            return back()->withErrors(['username' => 'User not found with provided details.'])
                ->withInput();
        }

        // Naya random transaction password generate karo
        $newTransactionPassword = Str::random(8);

        // Update karo
        $user->update([
            'transaction_password' => Hash::make($newTransactionPassword),
        ]);

        // Yahan aap email bhi bhej sakte ho
        // Mail::to($user->email)->send(new TransactionPasswordResetMail($newTransactionPassword));

        return redirect()->route('user.forgot-transaction-password')
            ->with('success', 'New transaction password generated successfully!')
            ->with('new_password', $newTransactionPassword);
    }
    public function welcomeLetter()
    {
        $userData = Session::get('user');

        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $token = Session::get('token');

        $response = Http::withToken($token)
            ->get($this->apiBaseUrl . '/profile');

        if ($response->successful()) {
            $data = $response->json();
            $user = json_decode(json_encode($data['data']));
        } else {
            $user = (object) $userData;
        }

        return view('pages.welcome-letter', compact('user'));
    }
    public function visitingCard()
    {
        $userData = Session::get('user');

        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = (object) $userData;

        return view('pages.visiting-card', compact('user'));
    }

// Common page for ID Card + Visiting Card
public function cards()
{
    $userData = Session::get('user');

    if (!$userData) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $user = (object) $userData;

    $profileImage = !empty($userData['profile_image'])
        ? $userData['profile_image']
        : null;

    // Convert profile image to base64 server-side so html2canvas can render it without CORS issues
    if ($profileImage && filter_var($profileImage, FILTER_VALIDATE_URL)) {
        try {
            $response = Http::timeout(5)->get($profileImage);
            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?: 'image/jpeg';
                $profileImage = 'data:' . $contentType . ';base64,' . base64_encode($response->body());
            }
        } catch (\Exception $e) {
            // Fall back to original URL
            Log::warning('Failed to fetch profile image for cards: ' . $e->getMessage());
        }
    }

    return view('pages.cards', compact('user', 'profileImage'));
}

// Visiting Card download karna
public function downloadVisitingCard(Request $request)
{
    $userData = Session::get('user');

    if (!$userData) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $user = (object) $userData;

    // HTML content ko capture karo
    $html = view('pages.visiting-card', compact('user'))->render();
    
    // PDF generate karne ke liye (agar snappy/pdf library use kar rahe ho)
    // Ya fir screenshot download ka option de sakte ho
    
    return response()->streamDownload(function () use ($html) {
        echo $html;
    }, 'visiting-card-'.$user->user_name.'.html');
}
public function signupAcknowledgement()
{
    $userData = Session::get('user');

    if (!$userData) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $token = Session::get('token');

    $response = Http::withToken($token)
        ->get($this->apiBaseUrl . '/profile');

    if ($response->successful()) {
        $data = $response->json();
        $user = json_decode(json_encode($data['data']));
    } else {
        $user = (object) $userData;
    }


    return view('pages.signup-acknowledgement', compact('user'));
}
}
