<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FundRequestStatusController extends Controller
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

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/fund-requests", [
                'user_id' => $userId,
                'status' => $request->status ?? '',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // dd($data);
                $fundRequests = collect($data['data'] ?? [])->map(fn($item) => (object) $item);
                
                return view('pages.user.fund-request-status', compact('fundRequests'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load fund requests');
        }

        return view('pages.user.fund-request-status', [
            'fundRequests' => collect()
        ]);
    }
}