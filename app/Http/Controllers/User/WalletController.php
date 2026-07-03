<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            // Fetch wallets
            $walletResponse = Http::timeout(10)->get("{$this->apiBaseUrl}/wallets", [
                'user_id' => $userId,
            ]);

            $wallets = collect();
            if ($walletResponse->successful()) {
                $wallets = collect($walletResponse->json()['data'] ?? [])->map(fn($item) => (object) $item);
            }

            return view('pages.user.wallets', compact('wallets'));
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load wallets');
        }

        return view('pages.user.wallets', [
            'wallets' => collect()
        ]);
    }

    public function getTransactions(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/wallet/transactions", [
                'user_id' => $userId,
                'reference_type' => $request->reference_type ?? '',
                'type' => $request->type ?? '',
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $transactions = collect($data['data'] ?? [])->map(fn($item) => (object) $item);
                $totals = (object) ($data['totals'] ?? ['credit' => 0, 'debit' => 0]);
                
                return view('pages.user.wallet-transactions', compact('transactions', 'totals'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load transactions');
        }

        return view('pages.user.wallet-transactions', [
            'transactions' => collect(),
            'totals' => (object)['credit' => 0, 'debit' => 0]
        ]);
    }

    /**
     * Show account summary page
     */
    public function accountSummary()
    {
        return view('pages.user.account-summary');
    }

    /**
     * ✅ FIXED: Get account summary data via API (not model)
     */
    public function getAccountSummaryData(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            // ✅ Call Admin Panel API
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/account-summary", [
                'user_id' => $userId,
                'type' => $request->get('type', 'all'),
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from API'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}