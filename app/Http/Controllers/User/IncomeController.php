<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
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

        if ($request->ajax()) {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/income-log", [
                'user_id' => $userId,
                'income_type' => $request->income_type ?? 'all',
                'from_date' => $request->from_date ?? '',
                'to_date' => $request->to_date ?? '',
                'per_page' => $request->per_page ?? 20,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch income logs.',
                ]);
            }

            return response()->json($response->json());
        }

        return view('pages.user.income-log', [
            'income_type' => $request->income_type ?? 'all',
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ]);
    }
}
