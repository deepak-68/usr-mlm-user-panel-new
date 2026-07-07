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

    private function getUserId()
    {
        return session('user.id');
    }

    private function api()
    {
        return Http::withToken(session('token'))->timeout(10);
    }

    public function directBusiness()
    {
        $userId = $this->getUserId();
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        try {
            $url = "{$this->apiBaseUrl}/referrals?user_id={$userId}";
            Log::info('Direct Business API URL: ' . $url);
            $response = $this->api()->get($url);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Direct Business API Success: ' . json_encode(array_keys($data ?? [])));
                $raw = $data['referrals'] ?? [];
                if (is_array($raw) && isset($raw['data'])) {
                    $directTeam = $raw;
                } elseif (is_array($raw)) {
                    $directTeam = ['data' => $raw, 'links' => [], 'meta' => []];
                } else {
                    $directTeam = ['data' => [], 'links' => [], 'meta' => []];
                }
                $stats = (object) ($data['stats'] ?? []);
                return view('pages.user.direct-business', compact('directTeam', 'stats'));
            }

            Log::error('Direct Business API Failed. Status: ' . $response->status() . ' Body: ' . substr($response->body(), 0, 500));
        } catch (\Exception $e) {
            Log::error('Direct Business API Exception: ' . $e->getMessage());
        }

        return view('pages.user.direct-business', [
            'directTeam' => ['data' => [], 'links' => [], 'meta' => []],
            'stats' => (object)['total' => 0, 'active' => 0, 'total_cc' => 0]
        ]);
    }

    public function downlineBusiness()
    {
        $userId = $this->getUserId();
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        try {
            $queryParams = [
                'user_id' => $userId,
                'search' => request('search'),
                'level' => request('level'),
                'position' => request('position'),
                'status' => request('status'),
            ];

            $response = $this->api()->get("{$this->apiBaseUrl}/team/downline", $queryParams);

            if ($response->successful()) {
                $data = $response->json();
                $teamMembers = collect($data['team_members']['data'] ?? $data['team_members'] ?? [])
                    ->map(function($item) {
                        $obj = (object) $item;
                        if (isset($obj->mlm_user) && is_array($obj->mlm_user)) {
                            $obj->mlm_user = (object) $obj->mlm_user;
                        }
                        if (isset($obj->mlm_user->sponsor) && is_array($obj->mlm_user->sponsor)) {
                            $obj->mlm_user->sponsor = (object) $obj->mlm_user->sponsor;
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
        $userId = $request->user_id ?? $this->getUserId();
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        try {
            $response = $this->api()
                ->withHeaders(['Accept' => 'application/json'])
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

    public function getUserProfileModal($userId)
    {
        try {
            $response = $this->api()->get("{$this->apiBaseUrl}/team/user-profile/{$userId}");

            if ($response->successful()) {
                $data = $response->json();
                $user = (object) ($data['user'] ?? []);
                $stats = $data['stats'] ?? [];

                return view('pages.user.partials.profile-modal', compact('user', 'stats'));
            }
        } catch (\Exception $e) {
            Log::error('Profile modal exception: ' . $e->getMessage());
        }

        return response()->view('pages.user.partials.profile-modal', [
            'user' => null,
            'stats' => []
        ], 404);
    }

    public function getUserTree($userId)
    {
        try {
            $response = $this->api()->get("{$this->apiBaseUrl}/team/genealogy?user_id={$userId}");

            if ($response->successful()) {
                $data = $response->json();
                $treeData = $data['tree_data'] ?? null;

                if ($treeData) {
                    return response()->json([
                        'success' => true,
                        'type' => 'genealogy',
                        'tree_data' => $treeData,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Tree HTML Error: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Tree data not found.'], 404);
    }

    public function getReferralList($userId)
    {
        try {
            $response = $this->api()->get("{$this->apiBaseUrl}/referrals?user_id={$userId}");

            if ($response->successful()) {
                $data = $response->json();
                $referrals = $data['referrals'] ?? [];

                return response()->json([
                    'success' => true,
                    'type' => 'referral',
                    'referrals' => $referrals,
                    'stats' => $data['stats'] ?? [],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Referral List Error: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Referral data not found.'], 404);
    }

    public function pendingPlacement(Request $request)
    {
        $userId = $this->getUserId();
        if (!$userId) return redirect()->route('login')->with('error', 'Please login first.');

        $pendingUsers = collect();
        $parents = collect();
        $paginationHtml = '';
        $sponsor = null;

        try {
            // Get the authenticated user's profile to find their sponsor
            $profileResponse = $this->api()->get("{$this->apiBaseUrl}/me");
            if ($profileResponse->successful()) {
                $profile = $profileResponse->json();
                $userData = $profile['user'] ?? $profile['data'] ?? $profile;
                $sponsorId = $userData['sponsor_id'] ?? null;
                if ($sponsorId) {
                    $sponsorResponse = Http::withToken(session('token'))->timeout(10)
                        ->get("{$this->apiBaseUrl}/profile?user_id={$sponsorId}");
                    if ($sponsorResponse->successful()) {
                        $sponsorData = $sponsorResponse->json();
                        $sponsor = (object) ($sponsorData['user'] ?? $sponsorData['data'] ?? $sponsorData);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Pending Placement Sponsor Fetch Error: ' . $e->getMessage());
        }

        try {
            // Build comma-separated sponsor IDs: own + sponsor's
            $sponsorIds = [$userId];
            $sponsorUserId = $sponsor->id ?? null;
            if ($sponsorUserId && $sponsorUserId != $userId) {
                $sponsorIds[] = $sponsorUserId;
            }

            $page = $request->get('page', 1);
            $response = $this->api()->get("{$this->apiBaseUrl}/holding-tank", [
                'sponsor_id' => implode(',', $sponsorIds),
                'per_page' => 10,
                'page' => $page,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $holdingData = $data['holding_users'] ?? [];
                $pendingUsers = collect($holdingData['data'] ?? $holdingData ?? [])
                    ->map(function ($item) {
                        $obj = (object) $item;
                        if (isset($obj->mlm_user) && is_array($obj->mlm_user)) {
                            $obj->mlm_user = (object) $obj->mlm_user;
                        }
                        if (isset($obj->mlmUser) && is_array($obj->mlmUser)) {
                            $obj->mlmUser = (object) $obj->mlmUser;
                        }
                        if (isset($obj->mlm_user->sponsor) && is_array($obj->mlm_user->sponsor)) {
                            $obj->mlm_user->sponsor = (object) $obj->mlm_user->sponsor;
                        }
                        if (isset($obj->mlmUser->sponsor) && is_array($obj->mlmUser->sponsor)) {
                            $obj->mlmUser->sponsor = (object) $obj->mlmUser->sponsor;
                        }
                        return $obj;
                    });
                $parents = collect($data['parents'] ?? [])->map(fn($p) => (object) $p);

                // Build simple pagination from API meta
                $meta = $holdingData['meta'] ?? $holdingData;
                $currentPage = $meta['current_page'] ?? $page;
                $lastPage = $meta['last_page'] ?? 1;
                $total = $meta['total'] ?? $pendingUsers->count();

                if ($lastPage > 1) {
                    $queryParams = $request->only('page');
                    $paginationHtml = '<nav><ul class="pagination pagination-sm justify-content-center mb-0">';
                    $prevDisabled = $currentPage <= 1 ? ' disabled' : '';
                    $paginationHtml .= '<li class="page-item' . $prevDisabled . '"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Prev</a></li>';
                    for ($i = 1; $i <= $lastPage; $i++) {
                        $active = $i == $currentPage ? ' active' : '';
                        $paginationHtml .= '<li class="page-item' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                    }
                    $nextDisabled = $currentPage >= $lastPage ? ' disabled' : '';
                    $paginationHtml .= '<li class="page-item' . $nextDisabled . '"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
                    $paginationHtml .= '</ul></nav>';
                }
            } else {
                Log::error('Pending Placement API Failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Pending Placement Exception: ' . $e->getMessage());
        }

        return view('pages.user.pending-placement', compact('pendingUsers', 'parents', 'paginationHtml', 'sponsor'));
    }

    public function pendingPlacementPlace(Request $request)
    {
        $userId = $this->getUserId();
        $token = session('token');

        if (!$userId || !$token) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Use the sponsor_id from the form if provided, otherwise fall back to the logged-in user
        $sponsorId = $request->sponsor_id ?? $userId;

        try {
            $response = Http::withToken($token)->timeout(30)
                ->post("{$this->apiBaseUrl}/holding-tank/place", [
                    'user_id' => $request->user_id,
                    'parent_id' => $request->parent_id,
                    'position' => $request->position,
                    'sponsor_id' => $sponsorId,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['success'] ?? false)) {
                return redirect()->route('user.pending-placement')
                    ->with('success', $body['message'] ?? 'User placed successfully!');
            }

            return back()->with('error', $body['message'] ?? 'Failed to place user.');
        } catch (\Exception $e) {
            Log::error('Pending Placement Place Error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}