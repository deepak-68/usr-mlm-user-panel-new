<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class UserMLMController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function directBusiness()
    {
        $userId = session('user_id'); 
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/referrals?user_id={$userId}");
            
            if ($response->successful()) {
                $data = $response->json();
                $directTeam = collect($data['referrals'] ?? [])->map(fn($item) => (object) $item);
                $stats = (object) ($data['stats'] ?? []);
                return view('pages.user.direct-business', compact('directTeam', 'stats'));
            }
        } catch (\Exception $e) {}

        return view('pages.user.direct-business', [
            'directTeam' => collect(),
            'stats' => (object)['total' => 0, 'active' => 0, 'total_cc' => 0]
        ]);
    }

    public function downlineBusiness()
    {
        $userId = session('user_id'); 
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        try {
            $queryParams = [
                'user_id' => $userId,
                'search' => request('search'),
                'level' => request('level'),
                'position' => request('position'),
                'status' => request('status'),
            ];

            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/team/downline", $queryParams);
           

            if ($response->successful()) {
                $data = $response->json();
                $teamMembers = collect($data['team_members']['data'] ?? $data['team_members'] ?? [])
                    ->map(function($item) {
                        $obj = (object) $item;
                        if (isset($obj->mlm_user) && is_array($obj->mlm_user)) {
                            $obj->mlm_user = (object) $obj->mlm_user;
                        }
                        return $obj;
                    });
                $stats = (object) ($data['stats'] ?? []);
                return view('pages.user.downline-business', compact('teamMembers', 'stats'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load downline data');
        }

        return view('pages.user.downline-business', [
            'teamMembers' => collect(),
            'stats' => (object)['total' => 0, 'level_1' => 0, 'level_2' => 0, 'left_leg' => 0, 'right_leg' => 0]
        ]);
    }

    public function genealogy(Request $request)
    {
        
        $userId = $request->user_id ?? session('user_id');
        
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        try {
            $response = Http::withHeaders(['Accept' => 'application/json'])
                ->timeout(10)
                ->get("{$this->apiBaseUrl}/team/genealogy?user_id={$userId}");

            if ($response->successful()) {
                $data = $response->json();
                $treeData = $data['tree_data'] ?? null;
                $rootUser = (object) ($data['root_user'] ?? []);
                
                return view('pages.user.genealogy', [
                    'treeData' => $treeData,
                    'rootUser' => $rootUser,
                    'apiBaseUrl' => $this->apiBaseUrl
                ]);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load genealogy tree: ' . $e->getMessage());
        }

        return view('pages.user.genealogy', [
            'treeData' => null, 
            'rootUser' => (object)[],
            'apiBaseUrl' => $this->apiBaseUrl
        ]);
    }

    /**
     * ✅ NEW: Returns HTML for profile modal (like admin panel)
     */
    /**
     * ✅ Returns HTML for profile modal
     */
    public function getUserProfileModal($userId)
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/team/user-profile/{$userId}");
            
            if ($response->successful()) {
                $data = $response->json();
                $user = (object) ($data['user'] ?? []);
                $stats = $data['stats'] ?? [];
                
                // ✅ FIX: Updated to match your actual folder structure
                return view('pages.user.partials.profile-modal', compact('user', 'stats'));
            }
        } catch (\Exception $e) {
            Log::error('Profile modal exception: ' . $e->getMessage());
        }
        
        // Fallback if API fails
        return response()->view('pages.user.partials.profile-modal', [
            'user' => null,
            'stats' => []
        ], 404);
    }

    public function getUserProfile($userId)
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/team/user-profile/{$userId}");
            
            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data);
            }
        } catch (\Exception $e) {}
        
        return response()->json(['success' => false, 'message' => 'Profile not found'], 404);
    }
        /**
     * Returns HTML for the tree of a specific user (for AJAX)
     */
    // public function getUserTreeHtml($userId)
    // {
    //     try {
    //         $response = Http::timeout(10)->get("{$this->apiBaseUrl}/team/genealogy?user_id={$userId}");
            
    //         if ($response->successful()) {
    //             $data = $response->json();
    //             $treeData = $data['tree_data'] ?? null;
                
    //             if ($treeData) {
    //                 // ✅ FIX: 'user.partials.tree-node'
    //                 $html = view('pages.user.partials.tree-node', [
    //                     'node' => $treeData, 
    //                     'isRoot' => true, 
    //                     'depth' => 0
    //                 ])->render();
                    
    //                 return response($html);
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         // Error ko log karein taaki pata chale kya gadbad hai
    //         Log::error('Tree HTML Error: ' . $e->getMessage());
    //     }
        
    //     return response('<div class="alert alert-warning text-center">Tree data not found.</div>', 404);
    // }
    public function getUserTree($userId)
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/team/genealogy?user_id={$userId}");
            
            if ($response->successful()) {
                $data = $response->json();
                $treeData = $data['tree_data'] ?? null;
                
                if ($treeData) {
                    // ✅ FIX: 'user.partials.tree-node'
                    $html = view('pages.user.partials.tree-node', [
                        'node' => $treeData, 
                        'isRoot' => true, 
                        'depth' => 0
                    ])->render();
                    
                    return response($html);
                }
            }
        } catch (\Exception $e) {
            // Error ko log karein taaki pata chale kya gadbad hai
            Log::error('Tree HTML Error: ' . $e->getMessage());
        }
        
        return response('<div class="alert alert-warning text-center">Tree data not found.</div>', 404);
    }
}