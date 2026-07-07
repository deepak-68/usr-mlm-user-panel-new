@extends('layouts.master')
@section('title','VSR | Dashboard')
@section('content')
 {{-- @dd($user->first_name) --}}
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Dashboard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">VSR</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- @dump(session()->all()) --}}
                    <!-- end page title -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                        <div id="mainToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body" id="toastMessage"></div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Welcome Message -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-primary-gradient text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-wrap gap-3">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-2 text-white">Welcome Back, {{ $user->first_name }} {{ $user->last_name }}!</h4>
                                            <p class="mb-3"> <strong>Track ID: </strong> <span class="badge text-bg-success">{{ $user->track_id }}</span> | <strong>Membership: </strong> <span class="badge text-bg-success">{{ ucwords(str_replace('_', ' ', $user->membership_type)) }}</span></p>

                                            <div class="datetime  fw-semibold fs-5 bg-white rounded text-dark d-inline px-2" id="datetime"></div>
                                             
                                        </div>
                                        <div class="flex-shrink-0">
                                            {{-- <i class="las la-user-circle" style="font-size: 60px;"></i> --}}
                                            <div class="btn-group rounded" role="group" aria-label="Basic example">
                                                <input type="hidden" id="referralLink" value="{{ route('register', ['sid' => $user->user_name]) }}" class="form-control" disabled >
                                                <button type="button" class="btn btn-light fw-semibold rounded-start" id="copyBtn"><i class="mdi mdi-content-copy"></i>  Copy referal link</button> 
                                                <button type="button" class="btn btn-success" id="shareBtn"><i class="mdi mdi-share-variant"></i> </button>
                                                <a href="{{ route('register', ['sid' => $user->user_name]) }}" target="_blank" type="button" class="btn btn-primary rounded-end"><span class="mdi mdi-open-in-new"></span> </a> 

                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Income KPIs (7 Types) -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="las la-chart-pie me-1"></i> Income Overview</h5>
                        </div>
                        @php
                            $kpis = $income_kpis ?? [];
                            $icons = [
                                'retail_income' => 'las la-store',
                                'direct_income' => 'las la-hand-holding-usd',
                                'matching_income' => 'las la-exchange-alt',
                                'level_income' => 'las la-layer-group',
                                'reward_tour_income' => 'las la-gift',
                                'repurchase_income' => 'las la-shopping-cart',
                                'rank_income' => 'las la-trophy',
                            ];
                            $colors = [
                                'retail_income' => 'primary',
                                'direct_income' => 'success',
                                'matching_income' => 'info',
                                'level_income' => 'warning',
                                'reward_tour_income' => 'danger',
                                'repurchase_income' => 'secondary',
                                'rank_income' => 'dark',
                            ];
                        @endphp
                        @foreach($kpis as $key => $kpi)
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card kpi-card kpi-{{ $colors[$key] ?? 'income' }} shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <p class="kpi-label mb-0">{{ $kpi->label ?? '' }}</p>
                                        <div class="kpi-icon badge bg-{{ $colors[$key] ?? 'primary' }} text-white">
                                            <i class="{{ $icons[$key] ?? 'las la-wallet' }}"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <div>
                                            <span class="badge bg-light text-dark me-1" title="Current CC">{{ number_format($kpi->current_cc, 2) }} CC</span>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 text-{{ $colors[$key] ?? 'primary' }}">₹{{ number_format($kpi->current_amount, 2) }}</h5>
                                        </div>
                                    </div>
                                    <small class="text-muted">Lifetime: {{ number_format($kpi->lifetime_total, 2) }} CC</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Section: Account Summary -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="las la-user-circle me-1"></i> Account Summary</h5>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-rank shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Current Rank</p>
                                            <h4 class="kpi-value">{{ $user_rank ?? 'Fresh' }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-trophy"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-business shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Self CC</p>
                                            <h4 class="kpi-value">{{ number_format($self_cc ?? 0, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-shopping-bag"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-wallet shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Fund Wallet</p>
                                            <h4 class="kpi-value">₹{{ number_format($fund_wallet ?? 0, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-coins"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-balance shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Left CC | Right CC</p>
                                            <div class="kpi-dual">
                                                <span class="chip chip-left">
                                                    <i class="las la-arrow-left"></i> {{ number_format($current_left_cc ?? 0, 0) }}
                                                </span>
                                                <span class="chip-divider">|</span>
                                                <span class="chip chip-right">
                                                    <i class="las la-arrow-right"></i> {{ number_format($current_right_cc ?? 0, 0) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-balance-scale"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-team shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Total Directs</p>
                                            <h4 class="kpi-value">{{ $direct_business ?? 0 }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-team shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Active Downline L | R</p>
                                            <div class="kpi-dual">
                                                <span class="chip chip-left">
                                                    <i class="las la-arrow-left"></i> {{ $left_team ?? 0 }}
                                                </span>
                                                <span class="chip-divider">|</span>
                                                <span class="chip chip-right">
                                                    <i class="las la-arrow-right"></i> {{ $right_team ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-sitemap"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-total shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Total Income (CC)</p>
                                            <h4 class="kpi-value">{{ number_format($total_income_cc ?? 0, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-wallet"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Order History -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Order History</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Order Number</th>
                                                    <th>Customer</th>
                                                    <th>Order Date</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>
                                                    <th>Invoice</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($order_history as $index => $order)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $order->order_number ?? 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                    <td>
                                                        @if(!empty($order->purchased_for_user_id) && $order->purchased_for_user_id != $order->user_id && !empty($order->purchased_for_user))
                                                            {{ $order->purchased_for_user->user_name ?? 'User #'.$order->purchased_for_user_id }}
                                                        @else
                                                            {{ $user->user_name }}
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d-m-Y') }}</td>
                                                    <td>{{ $order->order_type ?? 'SELF' }}</td>
                                                    <td>
                                                        @if(($order->status ?? '') === 'COMPLETED')
                                                            <span class="badge bg-success"><i class="las la-check-circle me-1"></i>Completed</span>
                                                        @elseif(($order->status ?? '') === 'CONFIRMED')
                                                            <span class="badge bg-info"><i class="las la-check me-1"></i>Confirmed</span>
                                                        @elseif(($order->status ?? '') === 'PENDING')
                                                            <span class="badge bg-warning text-dark"><i class="las la-clock me-1"></i>Pending</span>
                                                        @else
                                                            <span class="badge bg-danger"><i class="las la-times-circle me-1"></i>{{ $order->status ?? 'N/A' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-bold">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                                    <td>
                                                        @if(!empty($order->invoice))
                                                            <a href="{{ route('invoice.download', ['publicId' => $order->invoice->id]) }}"
                                                               class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="las la-download"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">No orders found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © VSR MLM.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                All Rights Reserved
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
        @endsection

    @push('scripts')
        <script>
            const referralLink = document.getElementById('referralLink').value;

            document.getElementById('copyBtn').addEventListener('click', function () {
                navigator.clipboard.writeText(referralLink).then(() => {
                    showToast('Referral link copied successfully!', 'success');

                    this.innerHTML = '<i class="mdi mdi-check"></i> Copied!';
                    setTimeout(() => {
                        this.innerHTML = '<i class="mdi mdi-content-copy"></i> Copy Referral Link';
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    showToast('Failed to copy link!', 'danger');
                });
            });

            document.getElementById('shareBtn').addEventListener('click', async function () {
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: 'Join using my referral link',
                            text: 'Register using my referral link:',
                            url: referralLink
                        });
                    } catch (err) {
                        console.log('Share cancelled');
                    }
                } else {
                    navigator.clipboard.writeText(referralLink).then(() => {
                        showToast('Sharing is not supported. Referral link copied instead!', 'success');
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                        showToast('Failed to copy link!', 'danger');
                    });
                }
            });


            function updateDateTime() {
                const now = new Date();

                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = now.getFullYear();

                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                const ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12;
                hours = hours ? hours : 12; // 0 becomes 12
                hours = String(hours).padStart(2, '0');

                document.getElementById("datetime").innerHTML =
                `<span class="mdi mdi-clock-time-nine-outline"></span> ${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${ampm}`;
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>
    @endpush