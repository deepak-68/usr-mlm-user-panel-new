@extends('layouts.master')
@section('title', 'Notifications')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-bell text-primary me-2"></i>NOTIFICATIONS
                        </h4>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
                            <i class="las la-check-double me-1"></i> Mark All as Read
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <label for="typeFilter" class="form-label mb-0 text-muted small">Filter:</label>
                            <select id="typeFilter" class="form-select form-select-sm" style="width:auto">
                                <option value="all">All Types</option>
                                <option value="purchase">Purchase</option>
                                <option value="income">Income</option>
                                <option value="rank">Rank</option>
                                <option value="reward">Reward</option>
                                <option value="registration">Registration</option>
                                <option value="withdrawal">Withdrawal</option>
                                <option value="ticket">Ticket</option>
                            </select>
                        </div>
                        <small class="text-muted" id="unreadSummary"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100" id="notificationsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Type</th>
                                    <th>Title / Message</th>
                                    <th style="width:160px">Date</th>
                                    <th style="width:80px">Status</th>
                                    <th style="width:50px">Action</th>
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

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
const typeIcons = {
    purchase:     'las la-shopping-bag',
    income:       'las la-wallet',
    rank:         'las la-trophy',
    reward:       'las la-gift',
    registration: 'las la-user-plus',
    withdrawal:   'las la-credit-card',
    ticket:       'las la-ticket',
};

const table = $('#notificationsTable').DataTable({
    processing: true,
    serverSide: true,
    ordering: false,
    ajax: {
        url: '{{ route("user.notifications") }}',
        data: function (d) {
            d.type = $('#typeFilter').val() || 'all';
        }
    },
    columns: [
        { data: 'DT_RowIndex', searchable: false, orderable: false },
        {
            data: 'type',
            render: function (data) {
                const icon = typeIcons[data] || 'las la-bell';
                const label = data ? data.charAt(0).toUpperCase() + data.slice(1) : '—';
                return `<span class="badge bg-primary rounded-circle p-2 me-1" style="font-size:12px"><i class="${icon}"></i></span> ${label}`;
            }
        },
        {
            data: null,
            render: function (data) {
                let html = `<strong class="${data.is_read ? '' : 'fw-bold'}">${data.title || ''}</strong>`;
                if (data.message) {
                    html += `<br><small class="text-muted">${data.message.length > 100 ? data.message.substring(0, 100) + '...' : data.message}</small>`;
                }
                return html;
            }
        },
        {
            data: 'created_at',
            render: function (data) {
                if (!data) return '—';
                const d = new Date(data);
                return d.toLocaleDateString('en-IN', {
                    day: '2-digit', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
            }
        },
        {
            data: 'is_read',
            render: function (data) {
                return data
                    ? '<span class="badge bg-secondary"><i class="las la-check me-1"></i>Read</span>'
                    : '<span class="badge bg-warning text-dark"><i class="las la-clock me-1"></i>New</span>';
            }
        },
        {
            data: 'id',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                if (row.is_read) return '';
                return `<button class="btn btn-sm btn-link mark-read p-0" data-id="${data}" title="Mark as read"><i class="las la-check-circle fs-5 text-success"></i></button>`;
            }
        }
    ],
    pageLength: 20,
    lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
    language: { emptyTable: 'No notifications yet.' },
    drawCallback: function () {
        updateBadge();
        $('.mark-read').off('click').on('click', function () {
            const id = $(this).data('id');
            const btn = $(this);
            fetch('{{ url("notifications") }}/' + id + '/read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).then(() => {
                const row = btn.closest('tr');
                table.row(row).invalidate().draw(false);
            });
        });
    }
});

$('#typeFilter').on('change', function () {
    table.ajax.reload();
});

$('#markAllReadBtn').on('click', function () {
    fetch('{{ route("user.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(() => {
        table.ajax.reload(null, false);
        updateBadge();
    });
});

function updateBadge() {
    fetch('{{ route("user.notifications.unread-count") }}', {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(json => {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            const count = json.unread_count || 0;
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline' : 'none';
        }
    })
    .catch(() => {});
}

// Poll unread count every 30s
setInterval(updateBadge, 30000);
</script>
@endpush