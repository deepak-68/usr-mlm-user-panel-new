@extends('layouts.master')
@section('title', 'Downline Rank')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-medal text-primary me-2"></i>DOWNLINE RANK
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="downlineRankForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Select Type</label>
                                <select name="type" id="filterType" class="form-select">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0" style="background: #b9f6ca;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="downlineRankTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">Sr.No</th>
                                    <th>UserId</th>
                                    <th>User Name</th>
                                    <th>Matching CC</th>
                                    <th>Date</th>
                                    <th>Awards & Rewards</th>
                                    <th>Status</th>
                                    <th>Rank</th>
                                    <th>Cash Bonus</th>
                                    <th>Rank Id</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>


function loadData(type = 'all') {
    dataTable.clear().draw();
    
    fetch(`{{ route('user.downline-rank.data') }}?type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach((item, index) => {
                    dataTable.row.add([
                        index + 1,
                        item.user_id || '-',
                        item.user_name || '-',
                        item.matching_cc || '0',
                        new Date(item.date).toLocaleDateString('en-GB'),
                        item.awards_rewards || '-',
                        `<span class="badge bg-${item.status === 'active' ? 'success' : 'warning'}">${item.status || 'Pending'}</span>`,
                        item.rank || '-',
                        item.cash_bonus || '0',
                        item.rank_id || '-'
                    ]);
                });
                dataTable.draw();
            }
        });
}

document.getElementById('downlineRankForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('filterType').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush