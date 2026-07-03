@if($user)
<div class="text-center">
    <div class="modal-profile-avatar">
        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
        <span class="modal-status-dot" style="background: {{ ($user->is_active ?? false) ? '#27ae60' : '#dc3545' }}"></span>
    </div>

    <h4 class="modal-title mt-2">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</h4>

    <p class="modal-subtitle">{{ '@' }}{{ $user->user_name ?? 'N/A' }} 
        ({{ (isset($stats['sponsor_id']) && $stats['sponsor_id'] !== 'Direct Seller') ? 'Sponsor: ' . $stats['sponsor_id'] : 'Direct Seller' }})
    </p>
</div>

<div class="stats-grid mt-3">
    <div class="stat-card">
        <div class="stat-label">User ID</div>
        <div class="stat-value" style="font-size: 13px;">{{ $user->user_name ?? 'N/A' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sponsor ID</div>
        <div class="stat-value" style="font-size: 13px;">{{ $stats['sponsor_id'] ?? 'N/A' }}</div>
    </div>
    <div class="stat-card right-bv">
        <div class="stat-label">Current Right CC</div>
        <div class="stat-value" style="font-size: 14px;">{{ $stats['current_right_cc'] ?? 0 }}</div>
    </div>
    <div class="stat-card left-bv">
        <div class="stat-label">Current Left CC</div>
        <div class="stat-value" style="font-size: 14px;">{{ $stats['current_left_cc'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Active Right Team</div>
        <div class="stat-value">{{ $stats['active_right_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Active Left Team</div>
        <div class="stat-value">{{ $stats['active_left_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Right Team</div>
        <div class="stat-value">{{ $stats['total_right_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Left Team</div>
        <div class="stat-value">{{ $stats['total_left_team'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Joining Date</div>
        <div class="stat-value" style="font-size: 13px;">{{ $stats['joined_date'] ?? 'N/A' }}</div>
    </div>
    <div class="stat-card personal-bv">
        <div class="stat-label">Self CC</div>
        <div class="stat-value">{{ $stats['personal_bv'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Package</div>
        <div class="stat-value" style="font-size: 13px;">{{ $stats['package'] ?? 'N/A' }}</div>
    </div>
    <div class="stat-card network-bv">
        <div class="stat-label">Level</div>
        <div class="stat-value">{{ $stats['level'] ?? 0 }}</div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Close</button>
    
    <button type="button" 
            class="btn flex-fill text-white" 
            onclick="loadUserTree({{ $user->id ?? 0 }})" 
            style="background: #1e3a5f; border: none;">
        <i class="las la-sitemap me-1"></i> Show Downline
    </button>
</div>
</div>
@else
<div class="alert alert-warning text-center m-3">
    <i class="las la-exclamation-circle me-2"></i>
    User profile not found.
</div>
@endif