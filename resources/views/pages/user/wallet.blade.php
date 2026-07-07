@extends('layouts.master')
@section('title', 'My Wallet')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-wallet text-primary me-2"></i>MY WALLET
                        </h4>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($walletData)
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-primary text-white shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                <i class="las la-coins fs-1"></i>
                            </div>
                            <h6 class="text-white-50 mb-2">Total CC Points</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($walletData['total_cc'], 2) }}</h2>
                            <small class="text-white-50">1 CC = ₹{{ number_format($walletData['conversion_rate'], 2) }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card bg-success text-white shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                <i class="las la-rupee-sign fs-1"></i>
                            </div>
                            <h6 class="text-white-50 mb-2">Converted Amount</h6>
                            <h2 class="mb-0 fw-bold">₹{{ number_format($walletData['converted_amount'], 2) }}</h2>
                            <small class="text-white-50">Total value at current rate</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card bg-info text-white shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                <i class="las la-wallet fs-1"></i>
                            </div>
                            <h6 class="text-white-50 mb-2">Fund Wallet Balance</h6>
                            <h2 class="mb-0 fw-bold">₹{{ number_format($walletData['fund_wallet_balance'], 2) }}</h2>
                            <small class="text-white-50">Available for transfer/withdrawal</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="las la-chart-pie me-2 text-primary"></i>CC Breakdown
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">Referral CC</td>
                                            <td class="text-end fw-bold text-primary">{{ number_format($walletData['self_cc'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">CC from Payouts (Commissions)</td>
                                            <td class="text-end fw-bold text-success">{{ number_format($walletData['payout_cc'], 2) }}</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="fw-bold fs-5">Total CC</td>
                                            <td class="text-end fw-bold fs-5 text-dark">{{ number_format($walletData['total_cc'], 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="las la-wallet fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No wallet data available</h5>
                            <p class="text-muted mb-0">Unable to load wallet information at this time.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection
