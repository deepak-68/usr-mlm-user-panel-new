<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BankDetailController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function show()
    {
        try {
            $userId = session('user_id');
            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
            }

            $response = Http::withToken(session('token'))
                ->timeout(10)
                ->get("{$this->apiBaseUrl}/user-bank-detail", [
                    'user_id' => $userId,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return response()->json([
                'success' => false,
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function save(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
        }

        try {
            $http = Http::withToken(session('token'))->timeout(30);

            $data = [
                'user_id' => $userId,
                'account_holder_name' => $request->account_holder_name,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
            ];

            if ($request->hasFile('bank_attachment')) {
                $file = $request->file('bank_attachment');
                $http = $http->attach(
                    'bank_attachment',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            $response = $http->post("{$this->apiBaseUrl}/user-bank-detail", $data);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message', 'Failed to save'),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
