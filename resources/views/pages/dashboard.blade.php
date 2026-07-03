@extends('layouts.master')
@section('title','VSR | Dashboard')
@section('content')
<!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
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
                                            <p class="mb-0"> <strong>Track ID: </strong> <span class="badge text-bg-success">{{ $user->track_id }}</span> | <strong>Membership: </strong> <span class="badge text-bg-success">{{ ucwords(str_replace('_', ' ', $user->membership_type)) }}</span></p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            {{-- <i class="las la-user-circle" style="font-size: 60px;"></i> --}}
                                            <div class="btn-group rounded" role="group" aria-label="Basic example">
                                                <input type="hidden" id="referralLink" value="{{ route('register', ['sid' => session('user_name')]) }}" class="form-control" disabled >
                                                <button type="button" class="btn btn-light fw-semibold rounded-start" id="copyBtn"><i class="mdi mdi-content-copy"></i>  Copy referal link</button> 
                                                {{-- <button type="button" class="btn btn-primary rounded-end" ><span class="mdi mdi-content-copy"></span></button>  --}}
                                                <a href="{{ route('register', ['sid' => session('user_name')]) }}" target="_blank" type="button" class="btn btn-primary rounded-end"><span class="mdi mdi-open-in-new"></span> </a> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-rank shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Highest Rank</p>
                                            <h4 class="kpi-value">{{ $userRank }}</h4>
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
                            <div class="card kpi-card kpi-income shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Current Income</p>
                                            <h4 class="kpi-value">₹{{ number_format($totalIncome, 2) }}</h4>
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

                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-business shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Self CC</p>
                                            <h4 class="kpi-value">{{ number_format($selfCC, 2) }}</h4>
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
                            <div class="card kpi-card kpi-percent shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Dual Matching Income</p>
                                            <h4 class="kpi-value">16%</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-percentage"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-income shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Direct Income</p>
                                            <h4 class="kpi-value">₹{{ number_format($directIncome, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-hand-holding-usd"></i>
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
                                            <h4 class="kpi-value">₹{{ number_format($fundWallet, 2) }}</h4>
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
                            <div class="card kpi-card kpi-income shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Matching Income</p>
                                            <h4 class="kpi-value">₹{{ number_format($matchingIncome, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-exchange-alt"></i>
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
                                            <p class="kpi-label">Current Left CC / Right CC</p>
                                            <div class="kpi-dual">
                                                <span class="chip chip-left">
                                                    <i class="las la-arrow-left"></i> {{ number_format($currentLeftCC, 0) }}
                                                </span>
                                                <span class="chip-divider">|</span>
                                                <span class="chip chip-right">
                                                    <i class="las la-arrow-right"></i> {{ number_format($currentRightCC, 0) }}
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
                                            <h4 class="kpi-value">{{ $directBusiness }}</h4>
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
                                            <p class="kpi-label">Active Downline Left | Right</p>
                                            <div class="kpi-dual">
                                                <span class="chip chip-left">
                                                    <i class="las la-arrow-left"></i> {{ $leftTeam }}
                                                </span>
                                                <span class="chip-divider">|</span>
                                                <span class="chip chip-right">
                                                    <i class="las la-arrow-right"></i> {{ $rightTeam }}
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
                            <div class="card kpi-card kpi-team shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Total Downline Left | Right</p>
                                            <div class="kpi-dual">
                                                <span class="chip chip-left">
                                                    <i class="las la-arrow-left"></i> {{ $totalDownlineLeft }}
                                                </span>
                                                <span class="chip-divider">|</span>
                                                <span class="chip chip-right">
                                                    <i class="las la-arrow-right"></i> {{ $totalDownlineRight }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-network-wired"></i>
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
                                            <p class="kpi-label">Total Direct Business</p>
                                            <h4 class="kpi-value">₹{{ number_format($totalDirectBusiness, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-chart-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-4 col-12 mb-3">
                            <div class="card kpi-card kpi-generation shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="kpi-label">Total Generation Income</p>
                                            <h4 class="kpi-value">₹{{ number_format($generationIncome, 2) }}</h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="kpi-icon">
                                                <i class="las la-layer-group"></i>
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
                                            <p class="kpi-label">Total Income</p>
                                            <h4 class="kpi-value">₹{{ number_format($totalIncome, 2) }}</h4>
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
                                                    <th>Order ID</th>
                                                    <th>Customer</th>
                                                    <th>Order Date</th>
                                                    <th>Delivery Type</th>
                                                    <th>Payment Status</th>
                                                    <th>Invoice</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($orderHistory as $index => $order)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $order->id ?? 'N/A' }}</td>
                                                    <td>{{ $user->user_name }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                                    <td>{{ $order->delivery_type ?? 'By Courier' }}</td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success">
                                                            {{ $order->payment_status ?? 'Approve' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary">Invoice</button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No orders found</td>
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
            document.getElementById('copyBtn').addEventListener('click', function() {
                const link = document.getElementById('referralLink').value;
                navigator.clipboard.writeText(link).then(() => {
                    showToast('Referal link copied successfully!', 'success');
                    this.innerText = 'Copied!';
                    setTimeout(() => {
                        this.innerText = 'Copy referal link';
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    // alert('Failed to copy link.');
                    showToast('Failed to copy link!', 'danger');
                });
            });
        </script>
    @endpush