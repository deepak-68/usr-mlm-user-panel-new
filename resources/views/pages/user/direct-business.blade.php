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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4 mt-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="las la-users fs-2 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-white-50 mb-1 text-uppercase small fw-semibold">Total Team</p>
                                        <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->total ?? 0 }}">{{ $stats->total ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="las la-check-circle fs-2 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-white-50 mb-1 text-uppercase small fw-semibold">Active Members</p>
                                        <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->active ?? 0 }}">{{ $stats->active ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info text-white shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="las la-coins fs-2 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-white-50 mb-1 text-uppercase small fw-semibold">Total CC</p>
                                        <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->total_cc ?? 0 }}">{{ number_format($stats->total_cc ?? 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="las la-times-circle fs-2 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-white-50 mb-1 text-uppercase small fw-semibold">Inactive</p>
                                        <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ ($stats->total ?? 0) - ($stats->active ?? 0) }}">{{ ($stats->total ?? 0) - ($stats->active ?? 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0"><i class="las la-filter me-1"></i> Filters</h5>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                <i class="las la-angle-down"></i>
                            </button>
                        </div>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <div class="card-body bg-light">
                            <form method="GET" action="" id="filterForm">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted fw-medium">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted fw-medium">Position</label>
                                        <select name="position" class="form-select">
                                            <option value="all" {{ request('position') == 'all' ? 'selected' : '' }}>All</option>
                                            <option value="left" {{ request('position') == 'left' ? 'selected' : '' }}>Left</option>
                                            <option value="right" {{ request('position') == 'right' ? 'selected' : '' }}>Right</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted fw-medium">Search</label>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by username or name..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="las la-search me-1"></i> Search
                                            </button>
                                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="las la-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Direct Team Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0"><i class="las la-list me-1"></i> Team Members</h5>
                        <span class="badge bg-primary fs-12 px-3 py-2">{{ count($directTeam['data'] ?? []) }} Members</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0" id="directTeamTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Position</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Joining Date</th>
                                        <th>Rank</th>
                                        <th class="text-center">Total CC</th>
                                        <th style="width: 200px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($directTeam['data'] ?? []) as $index => $member)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td><span class="fw-semibold">{{ $member['user_name'] ?? 'N/A' }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs rounded-circle bg-soft-primary d-flex align-items-center justify-content-center">
                                                            <span class="text-primary fw-bold">{{ substr($member['first_name'] ?? 'U', 0, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <p class="mb-0 fw-semibold">{{ $member['first_name'] ?? '' }} {{ $member['last_name'] ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($member['is_active'] ?? false)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="las la-sitemap me-1"></i>
                                                    {{ ucfirst($member['position_in_sponsor_leg'] ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>{{ $member['detail']['state'] ?? 'N/A' }}</td>
                                            <td>{{ $member['detail']['city'] ?? 'N/A' }}</td>
                                            <td>
                                                @if ($member['created_at'] ?? false)
                                                    {{ \Carbon\Carbon::parse($member['created_at'])->format('d-m-Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark fw-semibold">
                                                    <i class="las la-trophy me-1"></i>
                                                    {{ $member['current_rank']['rank']['name'] ?? $member['current_rank']['rank']['slug'] ?? 'Fresh' }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-bold text-primary">
                                                {{ number_format($member['payout_balance']->cc_balance ?? ($member['cc_balance'] ?? 0)) }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-sm btn-primary" title="View Purchase"
                                                        data-bs-toggle="modal" data-bs-target="#purchaseModal{{ $member['id'] ?? $index }}">
                                                        <i class="las la-shopping-cart"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success" title="View Retreat"
                                                        data-bs-toggle="modal" data-bs-target="#retreatModal{{ $member['id'] ?? $index }}">
                                                        <i class="las la-umbrella-beach"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5">
                                                <div class="py-5">
                                                    <i class="las la-users fs-1 text-muted d-block mb-3"></i>
                                                    <h5 class="text-muted">No Direct Team Members Found</h5>
                                                    <p class="text-muted mb-0">Start inviting people to build your team!</p>
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

    @foreach (($directTeam['data'] ?? []) as $index => $member)
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

@push('scripts')
<script>
$(document).ready(function () {

    $('#directTeamTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 12,
        lengthMenu: [[10, 12, 25, 50, -1], [10, 12, 25, 50, 'All']],
        columnDefs: [
            { orderable: false, targets: [10] }
        ],
        language: {
            search: '<i class="las la-search"></i>',
            searchPlaceholder: 'Search table...',
            lengthMenu: '_MENU_ records per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'No entries found',
            infoFiltered: '(filtered from _MAX_ total entries)',
            zeroRecords: 'No matching records found'
        }
    });

    $('.counter-value').each(function () {
        const $this = $(this);
        const target = parseInt($this.data('target'));
        if (target === 0) return;
        let current = 0;
        const step = Math.max(1, Math.floor(target / 50));
        const timer = setInterval(function () {
            current += step;
            if (current >= target) {
                $this.text(target.toLocaleString());
                clearInterval(timer);
            } else {
                $this.text(current.toLocaleString());
            }
        }, 25);
    });

});
</script>
@endpush