@extends('layouts.master')
@section('title', 'My Downline Business')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0 fs-20"><i class="las la-user-friends text-primary me-2"></i>MY DOWNLINE BUSINESS</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end mt-3 mt-md-0">
                                    <button class="btn btn-sm btn-primary" onclick="exportTable()">
                                        <i class="las la-download me-1"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📊 Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-2 col-6">
                    <div class="card bg-primary text-white border-0">
                        <div class="card-body text-center py-3">
                            <i class="las la-users fs-2 mb-2"></i>
                            <h3 class="mb-0">{{ $stats->total ?? 0 }}</h3>
                            <small>Total Team</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="card bg-info text-white border-0">
                        <div class="card-body text-center py-3">
                            <i class="las la-layer-group fs-2 mb-2"></i>
                            <h3 class="mb-0">{{ $stats->level_1 ?? 0 }}</h3>
                            <small>Level 1</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="card bg-info text-white border-0">
                        <div class="card-body text-center py-3">
                            <i class="las la-layer-group fs-2 mb-2"></i>
                            <h3 class="mb-0">{{ $stats->level_2 ?? 0 }}</h3>
                            <small>Level 2</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-success text-white border-0">
                        <div class="card-body text-center py-3">
                            <i class="las la-arrow-left fs-2 mb-2"></i>
                            <h3 class="mb-0">{{ $stats->left_leg ?? 0 }}</h3>
                            <small>Left Leg</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card bg-warning text-white border-0">
                        <div class="card-body text-center py-3">
                            <i class="las la-arrow-right fs-2 mb-2"></i>
                            <h3 class="mb-0">{{ $stats->right_leg ?? 0 }}</h3>
                            <small>Right Leg</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📋 Team Downline Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Team Downline</h5>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search & Filter -->
                    <form method="GET" action="{{ route('user.downline-business') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="las la-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by username or name..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="level" class="form-select">
                                    <option value="">All Levels</option>
                                    @for($i = 0; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ request('level')==$i?'selected':'' }}>Level {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="position" class="form-select">
                                    <option value="">All Positions</option>
                                    <option value="left" {{ request('position')=='left'?'selected':'' }}>LEFT</option>
                                    <option value="right" {{ request('position')=='right'?'selected':'' }}>RIGHT</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="las la-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="teamTable">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Parent</th>
                                    <th>Level</th>
                                    <th>Sale</th>
                                    <th>Position</th>
                                    <th>Registered on</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2" 
                                                     style="width:35px;height:35px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:12px;">
                                                    {{ strtoupper(substr($member->mlm_user->first_name ?? 'U',0,1).substr($member->mlm_user->last_name ?? '',0,1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $member->mlm_user->user_name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $member->mlm_user->first_name ?? '' }} {{ $member->mlm_user->last_name ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($member->mlm_user->sponsor))
                                                <span class="badge bg-info">{{ $member->mlm_user->sponsor->user_name ?? 'N/A' }}</span>
                                            @else
                                                <span class="text-muted">no parent</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $member->level ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <strong>0</strong>
                                        </td>
                                        <td>
                                            @if(($member->position ?? '') === 'left')
                                                <span class="badge bg-success">LEFT</span>
                                            @elseif(($member->position ?? '') === 'right')
                                                <span class="badge bg-warning text-dark">RIGHT</span>
                                            @else
                                                <span class="badge bg-secondary">{{ strtoupper($member->position ?? 'NONE') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ isset($member->mlm_user->created_at) ? \Carbon\Carbon::parse($member->mlm_user->created_at)->format('d M Y H:i') : 'N/A' }}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" 
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="viewTree({{ $member->mlm_user_id ?? 0 }}, '{{ $member->mlm_user->user_name ?? '' }}', 'genealogy'); return false;">
                                                            <i class="las la-sitemap me-2"></i> Genealogy Tree
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="viewTree({{ $member->mlm_user_id ?? 0 }}, '{{ $member->mlm_user->user_name ?? '' }}', 'referral'); return false;">
                                                            <i class="las la-project-diagram me-2"></i> Referral Tree
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="las la-user me-2"></i> View Profile
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="las la-inbox fs-1 mb-2 d-block"></i>
                                            <h5>No team members found</h5>
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

<!-- 🌳 Tree View Modal -->
<div class="modal fade" id="treeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="treeModalTitle">Tree View</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="treeModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading tree...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewTree(userId, userName, type) {
    const modal = document.getElementById('treeModal');
    const body = document.getElementById('treeModalBody');
    const title = document.getElementById('treeModalTitle');
    const bsModal = new bootstrap.Modal(modal);
    
    title.textContent = `${userName} - ${type === 'genealogy' ? 'Genealogy Tree' : 'Referral Tree'}`;
    body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading tree...</p></div>';
    bsModal.show();
    
    const url = type === 'genealogy' 
        ? `/api//team/user-downline/${userId}`
        : `/api/mlm/referrals/profile/${userId}`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            body.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        })
        .catch(() => {
            body.innerHTML = '<div class="alert alert-danger">Failed to load tree</div>';
        });
}

function exportTable() {
    const table = document.getElementById('teamTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        const cols = row.querySelectorAll('td, th');
        let csvRow = [];
        for (let col of cols) {
            csvRow.push('"' + col.textContent.trim() + '"');
        }
        csv.push(csvRow.join(','));
    }
    
    const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'team-downline.csv';
    a.click();
}
</script>
@endpush

@endsection