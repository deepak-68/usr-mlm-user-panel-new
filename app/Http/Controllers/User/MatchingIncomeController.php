<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchingIncomeController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    /**
     * Show Matching Income page
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        if($request->ajax()){
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/matching-income", [
                'user_id' => $userId,
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'total_credit' => 0,
                    'total_debit' => 0,
                ]);
            }

            $rows = collect($response->json()['data'] ?? []);

            // Search
            if ($search = $request->input('search.value')) {
                $rows = $rows->filter(function ($row) use ($search) {
                    return str_contains(strtolower($row['user']['user_name'] ?? ''), strtolower($search))
                        || str_contains(strtolower($row['type'] ?? ''), strtolower($search))
                        || str_contains((string)($row['amount'] ?? ''), $search);
                });
            }

            $recordsTotal = count($response->json()['data'] ?? []);
            $recordsFiltered = $rows->count();

            // Totals after filtering
            $totalCredit = $rows
                ->where('type', 'credit')
                ->sum('amount');

            $totalDebit = $rows
                ->where('type', 'debit')
                ->sum('amount');

            $start = (int) $request->start;
            $length = (int) $request->length;

            $data = $rows
                ->slice($start, $length)
                ->values()
                ->map(function ($row, $index) use ($start) {

                    $row['DT_RowIndex'] = $start + $index + 1;

                    $row['created_at'] = \Carbon\Carbon::parse($row['created_at'])
                        ->format('d-m-Y');

                    $row['credit'] = ($row['type'] === 'credit')
                        ? number_format($row['amount'], 2)
                        : '-';

                    $row['debit'] = ($row['type'] === 'debit')
                        ? number_format($row['amount'], 2)
                        : '-';

                    return $row;
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
                'total_credit' => number_format($totalCredit, 2),
                'total_debit' => number_format($totalDebit, 2),
            ]);
        }

        return view('pages.user.matching-income');
    }

    /**
     * Get Matching Income data via API
     */
    public function getMatchingIncomeData(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            // Call Admin Panel API
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/matching-income", [
                'user_id' => $userId,
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