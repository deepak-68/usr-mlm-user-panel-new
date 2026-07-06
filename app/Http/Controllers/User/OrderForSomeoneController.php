<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderForSomeoneController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $products = collect();
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/products");
            if ($response->successful()) {
                $data = json_decode($response->body());
                $products = $data->products ?? $data->data ?? $data ?? collect();
            }
        } catch (\Exception $e) {
            Log::error('Product API Error: ' . $e->getMessage());
        }

        return view('pages.user.order-for-someone', compact('products'));
    }

    public function placeOrder(Request $request)
    {
        $userId = Session::get('user_id');
        $token = Session::get('token');

        if (!$userId || !$token) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $request->validate([
            'target_track_id' => 'required|string',
            'product_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'Bearer ' . $token])
                ->post("{$this->apiBaseUrl}/order-for-someone", [
                    'ordering_user_id' => $userId,
                    'target_track_id' => $request->target_track_id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);

            $body = $response->json();

            if ($response->successful()) {
                return redirect()->route('user.order-for-someone')
                    ->with('success', $body['message'] ?? 'Order placed successfully!');
            }

            $message = $body['message'] ?? 'Failed to place order.';
            return back()->with('error', $message)->withInput();

        } catch (\Exception $e) {
            Log::error('OrderForSomeone Error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
