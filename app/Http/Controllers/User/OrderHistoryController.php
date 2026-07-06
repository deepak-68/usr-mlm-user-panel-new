<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderHistoryController extends Controller
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

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/order-history", [
                'user_id' => $userId,
                'type' => $request->type ?? 'all',
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'per_page' => 20,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $orders = $data['data'] ?? [];

                return view('pages.user.order-history', [
                    'orders' => $orders,
                    'type' => $request->type ?? 'all',
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                ]);
            }

            Log::error('Order API failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Order API Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to load orders');
        }

        return view('pages.user.order-history', [
            'orders' => [],
            'type' => 'all',
            'from_date' => null,
            'to_date' => null,
        ]);
    }
}
