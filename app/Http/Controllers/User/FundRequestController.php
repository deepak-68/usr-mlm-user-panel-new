<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FundRequestController extends Controller
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

        $userBank = null;
        $userBankList = [];

        try {
            $response = Http::withToken(session('token'))->timeout(10)->get("{$this->apiBaseUrl}/user-bank-detail", [
                'user_id' => $userId,
            ]);
            if ($response->successful()) {
                $data = $response->json()['data'] ?? null;
                if ($data) {
                    $userBank = $data;
                    $userBankList = [$data];
                }
            }
        } catch (\Exception $e) {
            // Bank data is optional
        }

        return view('pages.user.fund-request', compact('userId', 'userName', 'userBank', 'userBankList'));
    }

    /**
     * Get admin bank details for deposit
     */
    public function getBankDetails()
    {
        try {
            $url = "{$this->apiBaseUrl}/admin-bank-details";

            $response = Http::withToken(session('token'))->timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'data' => $data['data'] ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Admin API Error: Status ' . $response->status(),
                'details' => $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submit(Request $request)
    {
        $userId = session('user_id');
        $userName = session('user_name');
        $type = $request->input('type', 'deposit');

        Log::info('Fund Request Started', [
            'user_id' => $userId,
            'username' => $userName,
            'type' => $type,
            'request' => $request->except(['hash_code']),
            'api' => $this->apiBaseUrl,
        ]);

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            $data = [
                'user_id'         => $userId,
                'username'        => $userName,
                'amount'          => $request->amount,
                'remark'          => $request->remark,
                'mode_of_payment' => $request->mode_of_payment ?? 'NEFT',
                'type'            => $type,
            ];

            if ($type === 'withdrawal') {
                $data['user_bank_id'] = $request->user_bank_id;
            } else {
                $data['bank_detail_id']  = $request->bank_detail_id;
                $data['payment_mode']    = $request->payment_mode;
                $data['deposit_bank']    = $request->deposit_bank;
                $data['transaction_no']  = $request->transaction_no;
                $data['deposit_date']    = $request->deposit_date;
            }

            Log::info('Fund Request Payload', $data);

            $http = Http::withToken(session('token'))->timeout(30);

            if ($type !== 'withdrawal' && $request->hasFile('hash_code')) {
                $file = $request->file('hash_code');
                $http = $http->attach(
                    'hash_code',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            $url = "{$this->apiBaseUrl}/fund-request/submit";

            $response = $http->post($url, $data);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => $type === 'withdrawal'
                        ? 'Withdrawal request submitted successfully'
                        : 'Fund request submitted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message', 'Failed to submit request')
            ], $response->status());

        } catch (\Throwable $e) {
            Log::error('Fund Request Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
