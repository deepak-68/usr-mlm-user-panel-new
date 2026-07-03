@extends('layouts.master')
@section('title', 'My Direct Business')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box shadow-sm border-0">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0 fs-20"><i class="las la-user-tie text-primary me-2"></i>DIRECT TEAM</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end mt-3 mt-md-0">
                                        <span class="badge bg-primary fs-12 px-3 py-2">Total:
                                            {{ $stats->total ?? 0 }}</span>
                                        <span class="badge bg-success fs-12 px-3 py-2 ms-1">Active:
                                            {{ $stats->active ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <!-- Stats Cards -->
                <div class="row g-3 mb-4 mt-4">
                    <div class="col-md-3">
                        <div class="card card-animate border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="text-uppercase fw-medium text-muted mb-2">Total Team</p>
                                        <h4 class="mb-0">{{ $stats->total ?? 0 }}</h4>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-primary rounded-circle fs-4">
                                            <i class="las la-users"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-animate border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="text-uppercase fw-medium text-muted mb-2">Active Members</p>
                                        <h4 class="mb-0 text-success">{{ $stats->active ?? 0 }}</h4>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-success rounded-circle fs-4">
                                            <i class="las la-check-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-animate border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="text-uppercase fw-medium text-muted mb-2">Total CC</p>
                                        <h4 class="mb-0 text-info">{{ number_format($stats->total_cc ?? 0) }}</h4>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info rounded-circle fs-4">
                                            <i class="las la-coins"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-animate border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="text-uppercase fw-medium text-muted mb-2">Inactive</p>
                                        <h4 class="mb-0 text-danger">{{ ($stats->total ?? 0) - ($stats->active ?? 0) }}</h4>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-danger rounded-circle fs-4">
                                            <i class="las la-times-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card shadow-sm border-0">
                    <div class="card-body bg-soft-success">
                        <form method="GET" action="">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-medium">Select Type</label>
                                    <select name="status" class="form-select">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All
                                        </option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-medium">Search</label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by username, name, user ID..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="las la-search me-1"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- @dump($directTeam['data']) --}}

                <!-- Direct Team Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0 datatables"
                                id="directTeamTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">SrNo</th>
                                        <th>Username</th>
                                        {{-- <th>UserId</th> --}}
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Team</th>
                                        <th>Joining Date</th>
                                        <th>Package</th>
                                        <th>Activation Date</th>
                                        <th class="text-center">Self CC</th>
                                        <th class="text-center">Team CC</th>
                                        <th class="text-center">Total CC</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($directTeam['data'] as $index => $member)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td><span class="fw-semibold">{{ $member['user_name'] ?? 'N/A' }}</span></td>
                                            {{-- <td>
                                                <span class="badge bg-light text-dark">{{ $member['unique_id'] ?? ($member['user_id'] ?? ($member['id'] ?? 'N/A')) }}</span>
                                            </td> --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="avatar-xs rounded-circle bg-soft-primary d-flex align-items-center justify-content-center">
                                                            <span
                                                                class="text-primary fw-bold">{{ substr($member['first_name'] ?? 'U', 0, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <p class="mb-0 fw-semibold">{{ $member['first_name'] ?? '' }}
                                                            {{ $member['last_name'] ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{-- ✅ FIXED: Safe check for is_active --}}
                                                @if ($member['is_active'] ?? false)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Not Active</span>
                                                @endif
                                            </td>
                                            <td>{{ $member['detail']['state'] ?? 'N/A' }}</td>
                                            <td>{{ $member['detail']['city'] ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="las la-sitemap me-1"></i>
                                                    {{ ucfirst($member['position_in_sponsor_leg'] ?? ($member['position'] ?? 'Left Side')) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($member['created_at'] ?? false)
                                                    {{ \Carbon\Carbon::parse($member['created_at'])->format('d-m-Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-primary">{{ $member['package_name'] ?? 'Basic' }}</span>
                                            </td>
                                            <td>
                                                @if ($member['activated_at'] ?? false)
                                                    <span
                                                        class="text-success fw-semibold">{{ \Carbon\Carbon::parse($member['activated_at'])->format('d-m-Y') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center fw-bold">
                                                {{ number_format($member['self_cc'] ?? 0) }}
                                            </td>
                                            <td class="text-center text-muted">0</td>
                                            <td class="text-center fw-bold text-primary">
                                                {{ number_format($member['payout_balance']->cc_balance ?? ($member['cc_balance'] ?? 0)) }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#purchaseModal{{ $member['id'] ?? $index }}">
                                                        <i class="las la-shopping-cart"></i> View Purchase
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#retreatModal{{ $member['id'] ?? $index }}">
                                                        <i class="las la-umbrella-beach"></i> View Retreat
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center py-5">
                                                <div class="py-5">
                                                    <i class="las la-users fs-1 text-muted d-block mb-3"></i>
                                                    <h5 class="text-muted">No Direct Team Members Found</h5>
                                                    <p class="text-muted mb-0">Start inviting people to build your team!
                                                    </p>
                                                </div>
                                            </td>
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

    @foreach ($directTeam['data'] as $index => $member)
        <!-- Purchase Modal -->
        <div class="modal fade" id="purchaseModal{{ $member['id'] ?? $index }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Purchase Details - {{ $member['user_name'] ?? 'N/A' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>User:</strong> {{ $member['user_name'] ?? 'N/A' }}</p>
                        <p><strong>Package:</strong> {{ $member['package_name'] ?? 'N/A' }}</p>
                        <p><strong>Joining Date:</strong>
                            @if ($member['created_at'] ?? false)
                                {{ \Carbon\Carbon::parse($member['created_at'])->format('d-m-Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                        <p><strong>Amount:</strong> ₹{{ number_format($member['amount'] ?? 0) }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retreat Modal -->
        <div class="modal fade" id="retreatModal{{ $member['id'] ?? $index }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Retreat Details - {{ $member['user_name'] ?? 'N/A' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>User:</strong> {{ $member['user_name'] ?? 'N/A' }}</p>
                        <p><strong>Retreat Status:</strong> <span class="badge bg-warning">Pending</span></p>
                        <p><strong>Qualified Date:</strong> N/A</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
