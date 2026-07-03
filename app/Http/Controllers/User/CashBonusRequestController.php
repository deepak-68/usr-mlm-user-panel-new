<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CashBonusRequestController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        return view('pages.user.cash-bonus-request');
    }

    public function getCashBonusData(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/cash-bonus-request", [
                'user_id' => $userId,
                'type' => $request->type ?? 'all',
            ]);

            return $response->successful() 
                ? response()->json($response->json())
                : response()->json(['success' => false, 'message' => 'API Error'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}