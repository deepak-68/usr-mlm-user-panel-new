<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if ($request->ajax()) {
            $response = Http::withToken(session('token'))->timeout(10)->get("{$this->apiBaseUrl}/notifications", [
                'user_id' => $userId,
                'type' => $request->type ?? 'all',
                'per_page' => $request->per_page ?? 20,
            ]);

            if (!$response->successful()) {
                return response()->json(['success' => false, 'data' => []]);
            }

            return response()->json($response->json());
        }

        return view('pages.user.notifications');
    }

    public function unreadCount()
    {
        $userId = session('user_id');

        if (!$userId) {
            return response()->json(['unread_count' => 0]);
        }

        $response = Http::withToken(session('token'))->timeout(5)->get("{$this->apiBaseUrl}/notifications/unread-count", [
            'user_id' => $userId,
        ]);

        $count = $response->successful() ? ($response->json('unread_count') ?? 0) : 0;

        return response()->json(['unread_count' => $count]);
    }

    public function markAsRead($id)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['success' => false], 401);

        Http::withToken(session('token'))->timeout(5)->post("{$this->apiBaseUrl}/notifications/{$id}/read", [
            'user_id' => $userId,
        ]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['success' => false], 401);

        Http::withToken(session('token'))->timeout(5)->post("{$this->apiBaseUrl}/notifications/read-all", [
            'user_id' => $userId,
        ]);

        return response()->json(['success' => true]);
    }
}
