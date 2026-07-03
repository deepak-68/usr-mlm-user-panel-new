<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FundTransferController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        $userId = session('user_id');
        $userName = session('user_name');
        
        return view('pages.user.fund-transfer', compact('userId', 'userName'));
    }

    public function transfer(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        try {
            $response = Http::timeout(10)->post("{$this->apiBaseUrl}/fund-transfer/transfer", [
                'sender_id' => $userId,
                'receiver_username' => $request->receiver_username,
                'amount' => $request->amount,
                'transaction_password' => $request->transaction_password,
                'remark' => $request->remark ?? null,
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSentTransfers(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/fund-transfer/sent", [
                'user_id' => $userId,
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $transfers = collect($data['data'] ?? [])->map(fn($item) => (object) $item);
                
                return view('pages.user.fund-list', compact('transfers'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load transfers');
        }

        return view('pages.user.fund-list', [
            'transfers' => collect()
        ]);
    }

    public function getReceivedTransfers(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/fund-transfer/received", [
                'user_id' => $userId,
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $transfers = collect($data['data'] ?? [])->map(fn($item) => (object) $item);
                
                return view('pages.user.fund-receive-list', compact('transfers'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load received transfers');
        }

        return view('pages.user.fund-receive-list', [
            'transfers' => collect()
        ]);
    }

    public function getWalletBalance()
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['balance' => 0], 401);
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/fund-transfer/wallet-balance", [
                'user_id' => $userId,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            // Log error
        }

        return response()->json(['balance' => 0], 500);
    }
}