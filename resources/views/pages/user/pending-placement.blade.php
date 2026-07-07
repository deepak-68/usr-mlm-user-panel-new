@extends('layouts.master')
@section('title', 'Holding Tank')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20"><i class="las la-hourglass-half text-warning me-2"></i>HOLDING TANK</h4>
                        <p class="text-muted mb-0">Users pending placement in the binary tree — under you and your sponsor's team.</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3">
                <i class="las la-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3">
                <i class="las la-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
                    <h5 class="mb-0">Holding Tank List</h5>
                    <div class="input-group" style="width: 260px;">
                        <input type="text" class="form-control" id="tableSearch" placeholder="Search by name or username...">
                        <button class="btn btn-outline-secondary btn-sm" onclick="filterTable()"><i class="las la-filter"></i> FILTER</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="holdingTankTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Sponsor</th>
                                    <th>Status</th>
                                    <th>Registered At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingUsers as $item)
                                @php
                                    $user = $item->mlm_user ?? $item->mlmUser ?? null;
                                    $userSponsor = $user->sponsor ?? null;
                                @endphp
                                @if($user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $user->user_name ?? '' }}</code>
                                    </td>
                                    <td>
                                        @if($userSponsor)
                                            <small>{{ $userSponsor->first_name ?? '' }} {{ $userSponsor->last_name ?? '' }} ({{ $userSponsor->user_name ?? '' }})</small>
                                        @else
                                            <small class="text-muted">ROOT</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->created_at ?? $item->created_at)->format('d M Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#placementModal{{ $user->id }}">
                                            <i class="las la-sitemap"></i> Place
                                        </button>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="las la-check-circle text-success fs-3 mb-2 d-block"></i>
                                        No users in holding tank.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($paginationHtml)
                    <div class="card-footer d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">Showing {{ $pendingUsers->count() }} entries</small>
                        {!! $paginationHtml !!}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Placement Modals --}}
@foreach($pendingUsers as $item)
@php $user = $item->mlm_user ?? $item->mlmUser ?? null; @endphp
@if($user)
@php
    $sponsorId = ($user->sponsor_id ?? null) ?: session('user.id');
@endphp
<div class="modal fade" id="placementModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('user.pending-placement.place') }}" class="placement-form">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="sponsor_id" value="{{ $sponsorId }}">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-white border-0 pb-0">
                    <h5 class="modal-title">Placement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Placement Parent</label>
                        <select name="parent_id" class="form-select" required>
                            <option value="">Select Parent</option>
                            @foreach($parents as $p)
                                @if($p->id != $user->id)
                                <option value="{{ $p->id }}">
                                    {{ $p->user_name ?? $p->first_name }} ({{ $p->first_name ?? '' }} {{ $p->last_name ?? '' }})
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Position</label>
                        <select name="position" class="form-select" required>
                            <option value="left">LEFT</option>
                            <option value="right">RIGHT</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-bold">Place Now</span>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="place_now" checked>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="las la-check me-1"></i> PLACE
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function filterTable() {
    const input = document.getElementById('tableSearch');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('holdingTankTable');
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.cells.length < 2) return;
        const name = (row.cells[0]?.textContent || row.cells[0]?.innerText || '').toLowerCase();
        const username = (row.cells[1]?.textContent || row.cells[1]?.innerText || '').toLowerCase();
        row.style.display = name.includes(filter) || username.includes(filter) ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('tableSearch')?.addEventListener('keyup', filterTable);

    document.querySelectorAll('.placement-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Placing...';

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ env("API_BASE_URL") }}/holding-tank/place', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer {{ session("token") }}'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Placed!',
                        text: result.message || 'User placed successfully.',
                        confirmButtonColor: '#284a8a',
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: result.message || 'Failed to place user.',
                        confirmButtonColor: '#284a8a',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Unable to process request. Please try again.',
                    confirmButtonColor: '#284a8a',
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="las la-check me-1"></i> PLACE';
            }
        });
    });
});
</script>
@endpush

@endsection
