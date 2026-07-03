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
     protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api.base_url');
    }

     
    public function editProfile()
    {
       
        
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()
                ->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        }

        try {
            $response = Http::timeout(10)->get($this->baseUrl  . '/profile', ['user_id' => $userId]);
        // dd($response->json());


            if ($response->failed()) {
                Log::error('Profile API request failed', [
                    'user_id' => $userId,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return redirect()
                    ->back()
                    ->with('error', 'Unable to load your profile at the moment. Please try again later.');
            }

            $profile = $response->json();

            if (empty($profile) || empty($profile['data'])) {
                Session::flush();

                return redirect()
                    ->route('login')
                    ->with('error', 'Profile information could not be found. Please log in again.');
            }

            return view('pages.edit-my-profile', [
                'user' => $profile['data'],
                'data' => $profile,
            ]);

        } catch (Exception $e) {

            Log::error('Error while fetching profile', [
                'user_id' => $userId,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Something went wrong while loading your profile. Please try again.');
        }
    }

    // Profile update
    public function updateProfile(Request $request)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        } 

         $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
        ]);

        try {
            $response = Http::timeout(10)->post(
                $this->baseUrl . '/profile/update',
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
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = MlmUser::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // ✅ Validation - Ab yeh kaam karega
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Purani image delete karo
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // New image upload karo
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');

            $user->update([
                'profile_photo_path' => $path,
            ]);

            return redirect()->route('user.profile.image')
                ->with('success', 'Profile image updated successfully!');
        }

        return back()->with('error', 'No file uploaded.');
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
                    $this->baseUrl . '/change-password',
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
    $userId = Session::get('user_id');

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $user = MlmUser::with(['sponsor'])->find($userId);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    return view('pages.welcome-letter', compact('user'));
}
public function visitingCard()
{
    $userId = Session::get('user_id');

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $user = MlmUser::find($userId);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    return view('pages.visiting-card', compact('user'));
}

// Visiting Card download karna
public function downloadVisitingCard(Request $request)
{
    $userId = Session::get('user_id');
    $user = MlmUser::find($userId);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    // HTML content ko capture karo
    $html = view('pages.visiting-card-download', compact('user'))->render();
    
    // PDF generate karne ke liye (agar snappy/pdf library use kar rahe ho)
    // Ya fir screenshot download ka option de sakte ho
    
    return response()->streamDownload(function () use ($html) {
        echo $html;
    }, 'visiting-card-'.$user->user_name.'.html');
}
public function signupAcknowledgement()
{
    $userId = Session::get('user_id');

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    $user = MlmUser::with(['sponsor'])->find($userId);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    return view('pages.signup-acknowledgement', compact('user'));
}
}
