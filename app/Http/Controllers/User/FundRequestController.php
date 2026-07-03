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
        
        return view('pages.user.fund-request', compact('userId', 'userName'));
    }

    /**
     * ✅ FIXED: Get bank details and return as JSON
     */
    public function getBankDetails()
    {
        try {
            $url = "{$this->apiBaseUrl}/admin-bank-details";
            
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'data' => $data['data'] ?? []
                ]);
            }
            
            // ✅ Agar API fail hoti hai, toh exact status aur error return karein
            return response()->json([
                'success' => false,
                'message' => 'Admin API Error: Status ' . $response->status(),
                'details' => $response->body() // Yeh asli error dikhayega
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

        Log::info('Fund Request Started', [
            'user_id' => $userId,
            'username' => $userName,
            'request' => $request->except(['hash_code']),
            'api' => $this->apiBaseUrl,
        ]);

        if (!$userId) {
            Log::warning('Fund Request Failed - User not logged in');

            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {

            $data = [
                'user_id'         => $userId,
                'username'        => $userName,
                'bank_detail_id'  => $request->bank_detail_id,
                'payment_mode'    => $request->payment_mode,
                'amount'          => $request->amount,
                'remark'          => $request->remark,
                'mode_of_payment' => $request->mode_of_payment,
                'deposit_bank'    => $request->deposit_bank,
                'transaction_no'  => $request->transaction_no,
                'deposit_date'    => $request->deposit_date,
            ];

            Log::info('Fund Request Payload', $data);

            $http = Http::timeout(30);

            if ($request->hasFile('hash_code')) {

                $file = $request->file('hash_code');

                Log::info('Hash Code File Found', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);

                $http = $http->attach(
                    'hash_code',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            $url = "{$this->apiBaseUrl}/fund-request/submit";

            Log::info('Calling API', [
                'url' => $url
            ]);

            $response = $http->post($url, $data);

            Log::info('API Response Received', [
                'status' => $response->status(),
                'body'   => $response->body(),
                'json'   => $response->json(),
            ]);

            if ($response->successful()) {

                Log::info('Fund Request Submitted Successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'Fund request submitted successfully'
                ]);
            }

            Log::error('Fund Request API Error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $response->json('message', 'Failed to submit request')
            ], $response->status());

        } catch (\Throwable $e) {

            Log::error('Fund Request Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'debug'   => app()->environment('local') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ], 500);
        }
    }
}