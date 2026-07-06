<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function buyNow()
    {
        $userId = Session::get('user.id');
        $token = Session::get('token');
        $walletBalance = 0;
        $totalPurchased = 0;
        $products = collect();

        try {
            $headers = $token ? ['Authorization' => 'Bearer ' . $token] : [];
            $response = Http::timeout(10)
                ->withHeaders($headers)
                ->get("{$this->apiBaseUrl}/products");

            if ($response->successful()) {
                $data = json_decode($response->body());
                $products = $data->products ?? $data->data ?? $data ?? collect();
            }
        } catch (\Exception $e) {
            Log::error('Product API Error: ' . $e->getMessage());
        }

        // Wallet & order history only for authenticated users
        if ($userId && $token) {
            try {
                $walletResponse = Http::timeout(10)
                    ->withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->get("{$this->apiBaseUrl}/fund-transfer/wallet-balance", [
                        'user_id' => $userId
                    ]);

                if ($walletResponse->successful()) {
                    $walletData = $walletResponse->json();
                    $walletBalance = $walletData['balance'] ?? $walletData['data']['balance'] ?? 0;
                }
            } catch (\Exception $e) {
                Log::error('Wallet Balance API Error: ' . $e->getMessage());
            }

            try {
                $orderResponse = Http::timeout(10)
                    ->withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->get("{$this->apiBaseUrl}/order-history", [
                        'user_id' => $userId,
                        'per_page' => 1
                    ]);

                if ($orderResponse->successful()) {
                    $orderData = $orderResponse->json();
                    $totalPurchased = $orderData['data']['total'] ?? $orderData['total'] ?? 0;
                }
            } catch (\Exception $e) {
                Log::error('Order History API Error: ' . $e->getMessage());
            }
        }

        return view('pages.buy-now', compact(
            'products',
            'totalPurchased',
            'walletBalance'
        ));
    }

    public function purchase(Request $request)
    {
        $userId = Session::get('user_id');
        $token = Session::get('token');

        if (!$userId || !$token) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'Bearer ' . $token])
                ->post("{$this->apiBaseUrl}/purchase", [
                    'user_id' => $userId,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('buy-now')
                    ->with('success', $data['message'] ?? 'Order placed successfully!');
            }

            $error = $response->json();
            $message = $error['message'] ?? 'Purchase failed.';
            return back()->with('error', $message)->withInput();

        } catch (\Exception $e) {
            Log::error('Purchase API Error: ' . $e->getMessage());
            return back()->with('error', 'Error placing order: ' . $e->getMessage());
        }
    }
}
