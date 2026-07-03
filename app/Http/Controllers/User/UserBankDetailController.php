<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class UserBankDetailController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function index()
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/admin-bank-details");
            
            if ($response->successful()) {
                $data = $response->json();
                $bankDetails = collect($data['data'] ?? [])->map(fn($item) => (object) $item);
                
                return view('pages.user.admin-bank-detail', compact('bankDetails'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load bank details');
        }

        return view('pages.user.admin-bank-detail', [
            'bankDetails' => collect()
        ]);
    }
}