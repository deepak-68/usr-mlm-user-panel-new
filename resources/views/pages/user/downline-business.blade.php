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

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card bg-primary text-white shadow-lg border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-users fs-3 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-0 small text-uppercase fw-semibold">Total Team</p>
                                    <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->total ?? 0 }}">{{ $stats->total ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card bg-info text-white shadow-lg border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-layer-group fs-3 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-0 small text-uppercase fw-semibold">Level 1</p>
                                    <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->level_1 ?? 0 }}">{{ $stats->level_1 ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card bg-info text-white shadow-lg border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-layer-group fs-3 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-0 small text-uppercase fw-semibold">Level 2</p>
                                    <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->level_2 ?? 0 }}">{{ $stats->level_2 ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="card bg-success text-white shadow-lg border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-arrow-left fs-3 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-0 small text-uppercase fw-semibold">Left Leg</p>
                                    <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->left_leg ?? 0 }}">{{ $stats->left_leg ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="card bg-warning text-white shadow-lg border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-arrow-right fs-3 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-0 small text-uppercase fw-semibold">Right Leg</p>
                                    <h3 class="mb-0 text-white fw-bold counter-value" data-target="{{ $stats->right_leg ?? 0 }}">{{ $stats->right_leg ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Downline Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="las la-list me-1"></i> Team Downline</h5>
                    <span class="badge bg-primary fs-12 px-3 py-2">{{ count($teamMembers) }} Members</span>
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
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="las la-filter me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('user.downline-business') }}" class="btn btn-outline-secondary">
                                        <i class="las la-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="downlineTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>User</th>
                                    <th>Parent</th>
                                    <th>Level</th>
                                    <th>Sale</th>
                                    <th>Position</th>
                                    <th>Registered on</th>
                                    <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
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
                                                           onclick="viewTree({{ $member->mlm_user_id ?? $member->mlm_user->id ?? 0 }}, '{{ $member->mlm_user->user_name ?? '' }}', 'genealogy'); return false;">
                                                            <i class="las la-sitemap me-2"></i> Genealogy Tree
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="viewTree({{ $member->mlm_user_id ?? $member->mlm_user->id ?? 0 }}, '{{ $member->mlm_user->user_name ?? '' }}', 'referral'); return false;">
                                                            <i class="las la-project-diagram me-2"></i> Referral Tree
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="openProfile({{ $member->mlm_user_id ?? $member->mlm_user->id ?? 0 }}, '{{ $member->mlm_user->user_name ?? '' }}'); return false;">
                                                            <i class="las la-user me-2"></i> View Profile
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-5">
                                                <i class="las la-inbox fs-1 text-muted d-block mb-3"></i>
                                                <h5 class="text-muted">No team members found</h5>
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

<!-- Tree View Modal -->
<div class="modal fade" id="treeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="treeModalTitle">Tree View</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="treeModalBody" style="overflow-x: auto;">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading tree...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Profile Modal -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 pb-4 px-4">
                <div id="profileContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading profile...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    .tree-wrapper { text-align: center; padding: 20px; overflow-x: auto; }
    .tree-level { display: flex; justify-content: center; align-items: flex-start; gap: 30px; margin: 30px 0; position: relative; }
    .tree-node-wrapper { display: flex; flex-direction: column; align-items: center; position: relative; }
    .user-card { background: white; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); min-width: 220px; max-width: 240px; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; position: relative; }
    .user-card:hover { transform: translateY(-4px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); border-color: #eef2f7; }
    .user-card.root-user { border: 2px solid #eef2f7; }
    .crown-icon { position: absolute; top: -12px; right: 15px; color: #fbbf24; font-size: 18px; }
    .user-avatar { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; color: white; margin: 0 auto 12px; }
    .avatar-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .avatar-orange { background: linear-gradient(135deg, #f5a623 0%, #ff8c00 100%); }
    .user-name { font-weight: 700; font-size: 14px; color: #1a1d2e; margin-bottom: 3px; }
    .user-handle { font-size: 12px; color: #8a91a8; margin-bottom: 8px; }
    .status-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; color: #27ae60; }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; background: #27ae60; }
    .connector-vertical { width: 2px; height: 40px; background: #e2e8f0; margin: 0 auto; }
    .expand-btn { width: 28px; height: 28px; border-radius: 50%; background: white; border: 2px solid #e2e8f0; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; color: #8a91a8; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; margin: 10px auto 0; }
    .expand-btn:hover { background: #667eea; color: white; border-color: #667eea; }
    .expand-btn.expanded { background: #667eea; color: white; border-color: #667eea; }
    .expand-btn.expanded i { transform: rotate(180deg); }
    .expand-btn i { transition: transform 0.3s ease; }
    .empty-slot { background: #f8f9fa; border: 2px dashed #e2e8f0; border-radius: 16px; padding: 30px 20px; min-width: 220px; color: #a0aec0; font-size: 13px; text-align: center; }
    .empty-slot i { font-size: 32px; margin-bottom: 8px; opacity: 0.4; }
    .subtree { display: none; animation: fadeIn 0.4s ease; }
    .subtree.show { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .modal-profile-avatar { width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 700; margin: 0 auto 20px; position: relative; }
    .modal-status-dot { position: absolute; bottom: 5px; right: 5px; width: 18px; height: 18px; background: #27ae60; border-radius: 50%; border: 3px solid white; }
    .modal-title { font-size: 22px; font-weight: 700; color: #1a1d2e; margin-bottom: 5px; }
    .modal-subtitle { font-size: 14px; color: #8a91a8; margin-bottom: 30px; }
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 25px; }
    .stat-card { background: #f8f9fa; border-radius: 12px; padding: 18px 15px; text-align: center; }
    .stat-card.personal-bv { background: #f0fdf4; }
    .stat-card.left-bv { background: #eff6ff; }
    .stat-card.right-bv { background: #faf5ff; }
    .stat-card.network-bv { background: #f5f3ff; }
    .stat-label { font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; color: #8a91a8; margin-bottom: 8px; }
    .stat-value { font-size: 20px; font-weight: 700; color: #1a1d2e; }
    .stat-card.personal-bv .stat-value { color: #27ae60; }
    .stat-card.left-bv .stat-value { color: #3b82f6; }
    .stat-card.right-bv .stat-value { color: #8b5cf6; }
    .stat-card.network-bv .stat-value { color: #7c3aed; }
    .node-box { padding: 10px; border-radius: 8px; background: #fff; border: 2px solid #3498db; min-width: 180px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    .node-box.inactive { border-color: #e74c3c; }
    .empty-node { background: #f8f9fa !important; border: 2px dashed #ccc !important; color: #999 !important; min-width: 180px; padding: 10px; border-radius: 8px; }
    .Treant .collapse-switch { width: 24px !important; height: 24px !important; line-height: 24px !important; font-size: 16px !important; }
    .Treant .node > .collapse-switch { top: auto !important; bottom: -10px !important; left: 50% !important; right: auto !important; transform: translateX(-50%) !important; width: 20px !important; height: 20px !important; border-radius: 50%; background: #fff !important; z-index: 1 !important; }
    .avatar-placeholder { line-height: 48px; }
    .user-name p { font-size: 12px; color: #8a91a8; }
</style>

@push('scripts')
<script>
function convertToTreant(node, isRoot) {
    if (!node) return null;
    var result = {
        innerHTML: '<div class="custom-node text-center" data-user-id="' + (node.user_public_id || node.user_id || 0) + '">' +
            '<div class="user-avatar avatar-blue mb-2 border mx-auto">' +
                '<div class="avatar-placeholder">' +
                    (node.first_name ? node.first_name.charAt(0).toUpperCase() : 'U') +
                    (node.last_name ? node.last_name.charAt(0).toUpperCase() : '') +
                '</div>' +
            '</div>' +
            '<div class="user-name">' +
                (node.first_name || '') + ' ' + (node.last_name || '') +
                '<p class="small m-0">' + (node.user_name || 'N/A') + '</p>' +
            '</div>' +
            '<div class="mt-1">' +
                (node.is_active
                    ? '<span class="badge text-success"><span class="d-inline-block rounded-circle bg-success me-1" style="width:8px;height:8px;"></span>Active</span>'
                    : '<span class="badge text-danger"><span class="d-inline-block rounded-circle bg-danger me-1" style="width:8px;height:8px;"></span>Inactive</span>') +
            '</div>' +
        '</div>',
        HTMLclass: node.is_active ? 'node-box' : 'node-box inactive',
        collapsed: !isRoot,
        children: []
    };
    var children = [];
    if (node.left) {
        children.push(convertToTreant(node.left, false));
    } else {
        children.push({
            HTMLclass: 'empty-node',
            innerHTML: '<div class="text-center d-flex flex-column justify-content-center" style="height:127px;"><i class="las la-user fa-2x text-muted mb-2 mx-auto"></i><div>Empty Slot (LS)</div></div>'
        });
    }
    if (node.right) {
        children.push(convertToTreant(node.right, false));
    } else {
        children.push({
            HTMLclass: 'empty-node',
            innerHTML: '<div class="text-center d-flex flex-column justify-content-center" style="height:127px;"><i class="las la-user fa-2x text-muted mb-2 mx-auto"></i><div>Empty Slot (RS)</div></div>'
        });
    }
    result.children = children;
    return result;
}

function viewTree(userId, userName, type) {
    const modal = document.getElementById('treeModal');
    const body = document.getElementById('treeModalBody');
    const title = document.getElementById('treeModalTitle');
    const bsModal = new bootstrap.Modal(modal);
    
    if (type === 'referral') {
        title.textContent = `${userName} - Referral List`;
        body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading referrals...</p></div>';
        bsModal.show();
        fetch(`/user-referrals/${userId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    body.innerHTML = '<div class="alert alert-warning">Referral data not found.</div>';
                    return;
                }
                var referrals = data.referrals || [];
                var stats = data.stats || {};
                var html = '<div class="p-3">';
                html += '<div class="d-flex gap-3 mb-3 flex-wrap">';
                html += '<span class="badge bg-primary fs-13 px-3 py-2">Total: ' + (stats.total || 0) + '</span>';
                html += '<span class="badge bg-success fs-13 px-3 py-2">Active: ' + (stats.active || 0) + '</span>';
                html += '<span class="badge bg-info fs-13 px-3 py-2">Total CC: ' + (stats.total_cc || 0) + '</span>';
                html += '</div>';
                html += '<div class="table-responsive"><table class="table table-bordered table-striped table-hover align-middle mb-0">';
                html += '<thead class="table-dark"><tr><th>#</th><th>Username</th><th>Name</th><th>Status</th><th>Position</th><th>Joining Date</th><th>Action</th></tr></thead><tbody>';
                if (referrals.length === 0) {
                    html += '<tr><td colspan="7" class="text-center py-4"><h5 class="text-muted">No referrals found</h5></td></tr>';
                } else {
                    for (var i = 0; i < referrals.length; i++) {
                        var m = referrals[i];
                        var isArr = Array.isArray(referrals);
                        var member = isArr ? m : m;
                        html += '<tr>';
                        html += '<td class="text-center fw-bold">' + (i + 1) + '</td>';
                        html += '<td><span class="fw-semibold">' + (member.user_name || 'N/A') + '</span></td>';
                        html += '<td>' + (member.first_name || '') + ' ' + (member.last_name || '') + '</td>';
                        html += '<td>' + (member.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>') + '</td>';
                        html += '<td><span class="badge bg-info">' + (member.position_in_sponsor_leg ? member.position_in_sponsor_leg.charAt(0).toUpperCase() + member.position_in_sponsor_leg.slice(1) : 'N/A') + '</span></td>';
                        html += '<td>' + (member.created_at ? new Date(member.created_at).toLocaleDateString('en-GB') : 'N/A') + '</td>';
                        html += '<td><button class="btn btn-sm btn-primary" onclick="openProfile(' + (member.id || 0) + ', \'' + (member.user_name || '') + '\')"><i class="las la-user"></i> Profile</button></td>';
                        html += '</tr>';
                    }
                }
                html += '</tbody></table></div></div>';
                body.innerHTML = html;
            })
            .catch(function() {
                body.innerHTML = '<div class="alert alert-danger">Failed to load referrals</div>';
            });
        return;
    }
    
    title.textContent = `${userName} - Genealogy Tree`;
    body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading tree...</p></div>';
    bsModal.show();
    
    const url = `/user-tree/${userId}/html`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.tree_data) {
                body.innerHTML = '<div class="alert alert-warning">Tree data not found.</div>';
                return;
            }
            body.innerHTML = '<div id="treantTreeContainer" style="width:100%;min-height:450px;overflow:auto;padding:20px;"></div>';
            var treeStructure = convertToTreant(data.tree_data, true);
            new Treant({
                chart: {
                    container: '#treantTreeContainer',
                    rootOrientation: 'NORTH',
                    nodeAlign: 'CENTER',
                    connectors: { type: 'step' },
                    animateOnInit: true,
                    animateOnInitDelay: 300,
                    scrollbar: 'native',
                    collapsable: true
                },
                nodeStructure: treeStructure
            });
            document.getElementById('treantTreeContainer').addEventListener('click', function(e) {
                var node = e.target.closest('.custom-node');
                if (node && node.dataset.userId) {
                    openProfile(node.dataset.userId, '');
                }
            });
        })
        .catch(() => {
            body.innerHTML = '<div class="alert alert-danger">Failed to load tree</div>';
        });
}

function openProfile(userId, userName) {
    const modalEl = document.getElementById('userProfileModal');
    const modal = new bootstrap.Modal(modalEl);
    const contentEl = document.getElementById('profileContent');
    
    contentEl.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading profile...</p></div>';
    modal.show();
    
    fetch(`/user-profile/${userId}/modal`)
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.text();
        })
        .then(html => {
            contentEl.innerHTML = html;
        })
        .catch(() => {
            contentEl.innerHTML = '<div class="alert alert-danger m-3"><i class="las la-exclamation-circle me-2"></i>Failed to load profile.</div>';
        });
}

function exportTable() {
    const table = document.getElementById('downlineTable');
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

$(document).ready(function () {

    $(document).on('click', '.subtree-toggle', function () {
        const targetId = $(this).data('target');
        $('#' + targetId).toggleClass('show');
        $(this).toggleClass('expanded');
    });

    $('#downlineTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 12,
        lengthMenu: [[10, 12, 25, 50, -1], [10, 12, 25, 50, 'All']],
        columnDefs: [
            { orderable: false, targets: [7] }
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