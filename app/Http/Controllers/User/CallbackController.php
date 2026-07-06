<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        $whatsappNumber = null;
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/whatsapp-number");
            if ($response->successful()) {
                $whatsappNumber = $response->json('number');
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch WhatsApp number: ' . $e->getMessage());
        }

        return view('pages.user.schedule-callback', compact('whatsappNumber'));
    }

    public function submit(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Please login first.');
        }

        $request->validate([
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
            'issue_summary'  => 'nullable|string',
        ]);

        try {
            $response = Http::timeout(30)->post("{$this->apiBaseUrl}/schedule-callback", [
                'user_id'        => $userId,
                'preferred_date' => $request->preferred_date,
                'preferred_time' => $request->preferred_time,
                'issue_summary'  => $request->issue_summary,
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Callback scheduled successfully.');
            }

            return redirect()->back()->with('error', $response->json('message', 'Failed to schedule callback.'));

        } catch (\Exception $e) {
            Log::error('Callback Submit Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
