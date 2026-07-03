<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrievanceController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    // ─── Views ───────────────────────────────────────────────────────────────

    public function raiseTicket()
    {
        return view('pages.user.grievance.raise-ticket');
    }

    public function inbox()
    {
        return view('pages.user.grievance.inbox');
    }

    public function outbox()
    {
        return view('pages.user.grievance.outbox');
    }


    public function submitTicket(Request $request)
    {
        $userId   = session('user_id');
        $userName = session('user_name');

        Log::info('Submit Ticket Started', [
            'user_id' => $userId,
            'username' => $userName,
            'request' => $request->except(['attachment']),
        ]);

        if (!$userId) {
            Log::warning('Submit Ticket Failed - User not logged in');
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        $request->validate([
            'subject'     => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'required|string',
        ]);

        try {

            $payload = [
                'user_id'     => $userId,
                'username'    => $userName,
                'subject'     => $request->subject,
                'category'    => $request->category,
                'message'     => $request->description,
                'priority'    => $request->priority ?? 'medium',
            ];

            Log::info('Ticket API Payload', $payload);

            $httpRequest = Http::timeout(30);

            if ($request->hasFile('attachment')) {

                $file = $request->file('attachment');

                Log::info('Attachment Found', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);

                $httpRequest = $httpRequest->attach(
                    'attachment',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            Log::info('Calling API', [
                'url' => "{$this->apiBaseUrl}/raise-ticket"
            ]);

            $response = $httpRequest->post(
                "{$this->apiBaseUrl}/raise-ticket",
                $payload
            );

            Log::info('API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {

                Log::info('Ticket Raised Successfully', [
                    'response' => $response->json()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Ticket raised successfully',
                    'data' => $response->json('data'),
                ]);
            }

            Log::error('API Returned Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $response->json('message', 'Failed to raise ticket'),
            ], $response->status());

        } catch (\Exception $e) {

            Log::error('Grievance Submit Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again. ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─── API Proxy: Outbox (tickets raised by this user) ─────────────────────

    public function getOutboxData(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/outbox", [
                'user_id' => $userId,
                // 'status'  => $request->status ?? '',
            ]);

            return $response->successful()
                ? response()->json($response->json())
                : response()->json(['success' => false, 'message' => 'API Error: ' . $response->status()], 500);

        } catch (\Exception $e) {
            Log::error('Grievance Outbox Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── API Proxy: Inbox (admin replies to this user) ───────────────────────

    public function getInboxData(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/grievances/inbox", [
                'user_id' => $userId,
                'status'  => $request->status ?? '',
            ]);

            return $response->successful()
                ? response()->json($response->json())
                : response()->json(['success' => false, 'message' => 'API Error: ' . $response->status()], 500);

        } catch (\Exception $e) {
            Log::error('Grievance Inbox Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
