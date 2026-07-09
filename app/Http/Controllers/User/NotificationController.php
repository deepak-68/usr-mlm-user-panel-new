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
            // DataTables request — detect by 'draw' param
            if ($request->filled('draw')) {
                $perPage = $request->input('length', 20);
                $page = ($request->input('start', 0) / $perPage) + 1;

                $apiResponse = Http::withToken(session('token'))->timeout(10)->get("{$this->apiBaseUrl}/notifications", [
                    'user_id' => $userId,
                    'type' => $request->type ?? 'all',
                    'per_page' => $perPage,
                    'page' => $page,
                ]);

                if (!$apiResponse->successful()) {
                    return response()->json(['draw' => (int)$request->draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
                }

                $json = $apiResponse->json();
                $paginated = $json['data'] ?? [];

                $rows = $paginated['data'] ?? [];
                $start = (int)$request->input('start', 0);
                foreach ($rows as $i => &$row) {
                    $row['DT_RowIndex'] = $start + $i + 1;
                }

                return response()->json([
                    'draw' => (int)$request->draw,
                    'recordsTotal' => $paginated['total'] ?? 0,
                    'recordsFiltered' => $paginated['total'] ?? 0,
                    'data' => $rows,
                ]);
            }

            // Non-DataTables AJAX (e.g. custom fetch)
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

    public function recent()
    {
        $userId = session('user_id');

        if (!$userId) {
            return response()->json(['success' => false, 'data' => []]);
        }

        $response = Http::withToken(session('token'))->timeout(5)->get("{$this->apiBaseUrl}/notifications", [
            'user_id' => $userId,
            'unread_only' => true,
            'per_page' => 5,
        ]);

        if (!$response->successful()) {
            return response()->json(['success' => false, 'data' => []]);
        }

        return response()->json($response->json());
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
