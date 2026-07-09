
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ url ('assets/images/logo.webp')}}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ url ('assets/images/logo.webp')}}" alt="" height="21">
                        </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ url ('assets/images/logo.webp')}}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ url ('assets/images/logo.webp')}}" alt="" height="21">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button> 

            </div>               

            <div class="d-flex align-items-center">
                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle" data-toggle="fullscreen">
                        <i class='las la-expand fs-24'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle light-dark-mode">
                        <i class='las la-moon fs-24'></i>
                    </button>
                </div>

                <!-- Notification Bell Dropdown -->
                <div class="dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='las la-bell fs-24'></i>
                        <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; display: none;">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="notificationDropdown">
                        <div class="dropdown-head bg-primary rounded-top d-flex align-items-center justify-content-between px-3 py-2">
                            <h6 class="mb-0 text-white fs-14">Notifications</h6>
                            <span class="badge bg-light text-dark" id="dropdownBadge">0</span>
                        </div>
                        <div class="notification-list" id="recentNotifications" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center py-4 text-muted small">Loading...</div>
                        </div>
                        <a href="{{ route('user.notifications') }}" class="dropdown-item text-center fw-medium py-2 border-top">
                            View All
                        </a>
                    </div>
                </div>

                <div class="dropdown header-item">
                    <button type="button" class="btn border rounded-pill" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ session('user.profile_image') ? session('user.profile_image') . '?v=' . time() : 'https://ui-avatars.com/api/?name=' . session('user.first_name') . '+' . session('user.last_name') . '&background=random&size=128' }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block fw-medium user-name-text fs-16">{{ session('user.first_name') }} {{ session('user.last_name') }} <i class="las la-angle-down fs-12 ms-1"></i></span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bx bx-user fs-15 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                        {{-- <a class="dropdown-item" href="{{ route('user.registration') }}"><i class="bx bx-user fs-15 align-middle me-1"></i> <span key="t-profile">Registration</span></a> --}}

                        {{-- <a class="dropdown-item" href="#"><i class="bx  bx-user fs-15 align-middle me-1"></i> <span key="t-profile">Registration</span></a> --}}


                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off fs-15 align-middle me-1 text-danger"></i> 
                                <span key="t-logout">Logout</span>
                            </a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const badge = document.getElementById('notificationBadge');
    const dropdownBadge = document.getElementById('dropdownBadge');
    const recentList = document.getElementById('recentNotifications');

    function updateBadge(count) {
        const c = count || 0;
        if (badge) { badge.textContent = c; badge.style.display = c > 0 ? 'inline' : 'none'; }
        if (dropdownBadge) dropdownBadge.textContent = c;
    }

    function loadRecent() {
        if (!recentList) return;
        recentList.innerHTML = '<div class="text-center py-4 text-muted small">Loading...</div>';

        fetch('{{ route("user.notifications.recent") }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(json => {
            if (!json.success) {
                recentList.innerHTML = '<div class="text-center py-4 text-muted small">Failed to load.</div>';
                return;
            }
            const notifications = json.data?.data || [];
            updateBadge(json.unread_count);

            if (!notifications.length) {
                recentList.innerHTML = '<div class="text-center py-4 text-muted small"><i class="las la-bell-slash fs-4 d-block mb-1"></i>No notifications</div>';
                return;
            }

            recentList.innerHTML = '';
            notifications.forEach(n => {
                const icons = {
                    'purchase': 'las la-shopping-bag', 'income': 'las la-wallet',
                    'rank': 'las la-trophy', 'reward': 'las la-gift',
                    'registration': 'las la-user-plus', 'withdrawal': 'las la-credit-card',
                    'ticket': 'las la-ticket',
                };
                const icon = icons[n.type] || 'las la-bell';
                const time = new Date(n.created_at).toLocaleDateString('en-IN', {
                    day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
                });

                const item = document.createElement('a');
                item.href = '{{ route("user.notifications") }}';
                item.className = 'dropdown-item d-flex align-items-start gap-2 py-2 px-3 border-bottom';
                item.innerHTML = `
                    <span class="badge bg-primary rounded-circle p-1 mt-1" style="font-size: 12px; min-width: 26px; text-align: center;">
                        <i class="${icon}"></i>
                    </span>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex justify-content-between">
                            <strong class="text-dark small">${n.title}</strong>
                            <small class="text-muted ms-2 text-nowrap">${time}</small>
                        </div>
                        <small class="text-muted d-block text-truncate">${n.message || ''}</small>
                    </div>
                `;
                recentList.appendChild(item);
            });
        })
        .catch(() => {
            if (recentList) recentList.innerHTML = '<div class="text-center py-4 text-muted small">Error loading.</div>';
        });
    }

    // Initial load
    loadRecent();

    // Refresh unread count every 30s
    setInterval(loadRecent, 30000);
});
</script>