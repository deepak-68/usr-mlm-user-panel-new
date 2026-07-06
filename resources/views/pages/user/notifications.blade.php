@extends('layouts.master')
@section('title', 'Notifications')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-bell text-primary me-2"></i>NOTIFICATIONS
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Mark All Read -->
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
                    <i class="las la-check-double me-1"></i> Mark All as Read
                </button>
            </div>

            <!-- Notification List -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="notificationList">
                        <div class="text-center py-5" id="loadingNotifications">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading notifications...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small" id="tableInfo"></div>
                <nav>
                    <ul class="pagination mb-0" id="paginationLinks"></ul>
                </nav>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPage = 1;
let lastPage = 1;

function loadNotifications(page) {
    document.getElementById('loadingNotifications').style.display = '';

    fetch('{{ route("user.notifications") }}?page=' + (page || 1), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(json => {
        document.getElementById('loadingNotifications').style.display = 'none';

        if (!json.success) {
            document.getElementById('notificationList').innerHTML =
                '<div class="text-center py-5 text-muted">Failed to load notifications.</div>';
            return;
        }

        const data = json.data;
        const notifications = data.data || [];
        const list = document.getElementById('notificationList');

        if (!notifications.length) {
            list.innerHTML = '<div class="text-center py-5 text-muted"><i class="las la-bell-slash fs-1 d-block mb-2"></i>No notifications yet.</div>';
            document.getElementById('tableInfo').textContent = '0 entries';
            document.getElementById('paginationLinks').innerHTML = '';
            return;
        }

        list.innerHTML = '';
        notifications.forEach(n => {
            const typeIcons = {
                'purchase': 'las la-shopping-bag',
                'income': 'las la-wallet',
                'rank': 'las la-trophy',
                'reward': 'las la-gift',
                'registration': 'las la-user-plus',
                'withdrawal': 'las la-credit-card',
                'ticket': 'las la-ticket',
            };
            const icon = typeIcons[n.type] || 'las la-bell';
            const time = new Date(n.created_at).toLocaleDateString('en-IN', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            const div = document.createElement('div');
            div.className = `list-group-item list-group-item-action d-flex align-items-start gap-3 ${n.is_read ? '' : 'bg-light'}`;
            div.innerHTML = `
                <div class="flex-shrink-0">
                    <span class="badge bg-primary rounded-circle p-2" style="font-size: 16px;">
                        <i class="${icon}"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1 ${n.is_read ? '' : 'fw-bold'}">${n.title}</h6>
                        <small class="text-muted">${time}</small>
                    </div>
                    <p class="mb-1 text-muted small">${n.message || ''}</p>
                </div>
                ${!n.is_read ? `<button class="btn btn-sm btn-link mark-read" data-id="${n.id}"><i class="las la-check text-success"></i></button>` : ''}
            `;
            list.appendChild(div);
        });

        // Mark individual as read
        list.querySelectorAll('.mark-read').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch('{{ route("user.notifications.mark-read", "") }}/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(() => {
                    this.closest('.list-group-item').classList.remove('bg-light');
                    this.closest('.list-group-item').querySelector('h6').classList.remove('fw-bold');
                    this.remove();
                    updateBadge();
                });
            });
        });

        // Pagination
        currentPage = data.current_page || 1;
        lastPage = data.last_page || 1;
        document.getElementById('tableInfo').textContent = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total || 0}`;

        const pagination = document.getElementById('paginationLinks');
        pagination.innerHTML = '';
        for (let p = 1; p <= lastPage; p++) {
            const li = document.createElement('li');
            li.className = `page-item ${p === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" data-page="${p}">${p}</a>`;
            li.querySelector('a').addEventListener('click', e => {
                e.preventDefault();
                loadNotifications(p);
            });
            pagination.appendChild(li);
        }
    })
    .catch(err => {
        document.getElementById('loadingNotifications').style.display = 'none';
        document.getElementById('notificationList').innerHTML =
            '<div class="text-center py-5 text-danger">Error loading notifications.</div>';
        console.error(err);
    });
}

document.getElementById('markAllReadBtn').addEventListener('click', function() {
    fetch('{{ route("user.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(() => {
        document.querySelectorAll('#notificationList .list-group-item').forEach(item => {
            item.classList.remove('bg-light');
            item.querySelector('h6')?.classList.remove('fw-bold');
            item.querySelector('.mark-read')?.remove();
        });
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

loadNotifications(1);
</script>
@endpush
