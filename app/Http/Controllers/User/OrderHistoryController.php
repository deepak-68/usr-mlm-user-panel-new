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

    /**
     * ✅ Fetch data directly in index() and pass to view
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            $type = $request->type ?? 'all';
            
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/order-history", [
                'user_id' => $userId,
                'type' => $type,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $orders = collect($data['data'] ?? [])->map(fn($item) => (object) $item);
                
                return view('pages.user.order-history', compact('orders'));
            }
            
            Log::error('Order API failed: ' . $response->body());
            
        } catch (\Exception $e) {
            Log::error('Order API Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to load orders');
        }

        return view('pages.user.order-history', [
            'orders' => collect()
        ]);
    }
}