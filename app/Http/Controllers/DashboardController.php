<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;


class DashboardController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }
    
    public function index()
    {
                // dd(Session::all());
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login');
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->get($this->apiBaseUrl . '/dashboard');

        if ($response->unauthorized()) {

            Session::flush();

            return redirect()->route('login');
        }

        if (!$response->successful()) {
            abort(500, 'Unable to load dashboard.');
        }

        $data = json_decode(json_encode($response->json('data')));

        return view('pages.dashboard', (array) $data);

    }


    public function buyNow() 
    {
       
        $apiBaseUrl = env('API_BASE_URL');
        
        try {
           
            $response = Http::timeout(10)->get("{$apiBaseUrl}/products");
            
            if ($response->successful()) {
                $apiData = $response->json();
                
                
                $products = collect($apiData['products'] ?? [])->map(function ($item) {
                    return (object) $item;
                });
                
                $productCategory = collect($apiData['product_category'] ?? [])->map(function ($item) {
                    return (object) $item;
                });
            } else {
                $products = collect();
                $productCategory = collect();
                session()->flash('error', 'Products load nahi ho paye. API Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $products = collect();
            $productCategory = collect();
            session()->flash('error', 'API Connection Error: ' . $e->getMessage());
        }

    
        return view('user.buy-now', compact(
            'products', 
            'productCategory',
            'walletBalance',
            'maxProducts',
            'totalPurchased',
            'remainingProducts'
        ));
    }
}