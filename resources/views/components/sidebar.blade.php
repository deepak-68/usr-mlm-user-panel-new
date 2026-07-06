<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ url('assets/images/logo.webp') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ url('assets/images/logo.webp') }}" class="img-fluid" alt="" height="21">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ url('assets/images/logo.webp') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ url('assets/images/logo.webp') }}" alt="" height="21">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <!-- Dashboard (Active Example) -->
                <li class="nav-item mm -active"> <!-- Added mm-active for parent -->
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"> <!-- Added active class -->
                        <i class="las la-tachometer-alt"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('buy-now') ? 'active' : '' }}" href="{{ route('buy-now') }}">
                        <i class="las la-shopping-cart"></i> <span data-key="t-dashboard">Buy Now</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('user.order-for-someone*') ? 'active' : '' }}" href="{{ route('user.order-for-someone') }}">
                        <i class="las la-user-plus"></i> <span>Order for Someone</span>
                    </a>
                </li>

                @php
                    $profileMenus = [
                        [
                            'route' => 'user.profile',
                            'icon' => 'las la-user-edit',
                            'title' => 'Update Profile',
                        ],
                        // [
                        //     'route' => 'user.profile.image',
                        //     'icon' => 'las la-image',
                        //     'title' => 'Edit Profile Image',
                        // ],
                        // [
                        //     'route' => 'user.change-password',
                        //     'icon' => 'las la-key',
                        //     'title' => 'Change Password',
                        // ],
                        [
                            'route' => 'user.change-transaction-password',
                            'icon' => 'las la-lock',
                            'title' => 'Change Tran. Password',
                        ],
                        [
                            'route' => 'user.forgot-transaction-password',
                            'icon' => 'las la-unlock-alt',
                            'title' => 'Forgot Tran. Password',
                        ],
                        [
                            'route' => 'user.welcome-letter',
                            'icon' => 'las la-envelope-open-text',
                            'title' => 'Welcome Letter',
                        ],
                        [
                            'route' => '#',
                            'icon' => 'las la-id-badge',
                            'title' => 'ID Card',
                        ],
                        [
                            'route' => 'user.visiting-card',
                            'icon' => 'las la-address-card',
                            'title' => 'Visiting Card',
                        ],
                        [
                            'route' => 'user.signup-acknowledgement',
                            'icon' => 'las la-file-signature',
                            'title' => 'Sign Up Acknowledgement',
                        ],
                    ];

                    $profileRouteNames = collect($profileMenus)
                        ->pluck('route')
                        ->filter(fn ($route) => $route !== '#')
                        ->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs(...$profileRouteNames) ? 'active' : '' }}"
                        href="#sidebarInvoiceManagement"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs(...$profileRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarInvoiceManagement">

                        <i class="las la-user-circle"></i>
                        <span data-key="t-invoices">Profile</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs(...$profileRouteNames) ? 'show' : '' }}"
                        id="sidebarInvoiceManagement">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($profileMenus as $item)
                                <li class="nav-item">
                                    <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                                        class="nav-link {{ $item['route'] !== '#' && request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>

                @php
                    $teamRoutes = [
                        [
                            'route' => 'user.direct-business',
                            'icon' => 'las la-user-tie',
                            'title' => 'My Direct Business',
                        ],
                        [
                            'route' => 'user.downline-business',
                            'icon' => 'las la-user-friends',
                            'title' => 'My Downline Business',
                        ],
                        [
                            'route' => 'user.genealogy',
                            'icon' => 'las la-sitemap',
                            'title' => 'Genealogy',
                        ],
                    ];

                    $teamRouteNames = collect($teamRoutes)->pluck('route')->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs(...$teamRouteNames) ? 'active' : '' }}"
                        href="#sidebarAuthentication"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs(...$teamRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarAuthentication">

                        <i class="las la-users"></i>
                        <span data-key="t-authentication">My Team</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs(...$teamRouteNames) ? 'show' : '' }}"
                        id="sidebarAuthentication">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($teamRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ route($item['route']) }}"
                                        class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>

                @php
                    $fundRoutes = [
                        [
                            'route' => 'user.admin-bank-detail',
                            'icon' => 'las la-university',
                            'title' => 'Admin Bank Detail'
                        ],
                        [
                            'route' => 'user.fund-summary',
                            'icon' => 'las la-chart-pie',
                            'title' => 'Fund Summary'
                        ],
                        [
                            'route' => 'user.fund-request',
                            'icon' => 'las la-hand-holding-usd',
                            'title' => 'Fund Request'
                        ],
                        [
                            'route' => 'user.fund-request-status',
                            'icon' => 'las la-clipboard-list',
                            'title' => 'Fund Request Status'
                        ],
                        [
                            'route' => 'user.fund-history',
                            'icon' => 'las la-history',
                            'title' => 'Fund History'
                        ],
                        [
                            'route' => 'user.fund-transfer',
                            'icon' => 'las la-exchange-alt',
                            'title' => 'Fund Transfer'
                        ],
                        [
                            'route' => 'user.fund-list',
                            'icon' => 'las la-list',
                            'title' => 'Fund List'
                        ],
                        [
                            'route' => 'user.fund-receive-list',
                            'icon' => 'las la-inbox',
                            'title' => 'Fund Receive List'
                        ],
                    ];

                    $fundRouteNames = collect($fundRoutes)->pluck('route')->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs($fundRouteNames) ? 'active' : '' }}"
                        href="#sidebarFunds"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs($fundRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarFunds">

                        <i class="las la-coins"></i>
                        <span data-key="t-authentication">Funds</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs($fundRouteNames) ? 'show' : '' }}"
                        id="sidebarFunds">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($fundRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ route($item['route']) }}"
                                        class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>

                <!-- Wallet Dropdown -->
                @php
                    $walletRoutes = [
                        [
                            'route' => 'user.account.summary',
                            'icon' => 'las la-file-invoice-dollar',
                            'title' => 'Account Summary',
                        ],
                        [
                            'route' => 'user.direct-income',
                            'icon' => 'las la-dollar-sign',
                            'title' => 'Direct Income',
                        ],
                        [
                            'route' => 'user.matching-income',
                            'icon' => 'las la-euro-sign',
                            'title' => 'Matching Income',
                        ],
                        [
                            'route' => 'user.income-log',
                            'icon' => 'las la-history',
                            'title' => 'Referral Income Log',
                        ],
                        [
                            'route' => 'user.notifications',
                            'icon' => 'las la-bell',
                            'title' => 'Notifications',
                        ],
                        // [
                        //     'route' => 'user.cash-bonus-request',
                        //     'icon' => 'las la-gift',
                        //     'title' => 'Cash Bonus Request',
                        // ],
                        // [
                        //     'route' => 'user.claim-cash-request',
                        //     'icon' => 'las la-donate',
                        //     'title' => 'Claim Cash Request',
                        // ],
                        // [
                        //     'route' => 'user.cash-bonus-history',
                        //     'icon' => 'las la-history',
                        //     'title' => 'Cash Bonus History',
                        // ],
                        // [
                        //     'route' => 'user.generation-income',
                        //     'icon' => 'las la-chart-line',
                        //     'title' => 'Generation Income',
                        // ],
                        // [
                        //     'route' => 'user.awards-rewards',
                        //     'icon' => 'las la-trophy',
                        //     'title' => 'Awards and Rewards',
                        // ],
                        // [
                        //     'route' => 'user.downline-rank',
                        //     'icon' => 'las la-medal',
                        //     'title' => 'Downline Rank',
                        // ],
                        // [
                        //     'route' => 'user.weekly-payout',
                        //     'icon' => 'las la-calendar-week',
                        //     'title' => 'Weekly Payout',
                        // ],
                        // [
                        //     'route' => 'user.retreat-tours',
                        //     'icon' => 'las la-plane',
                        //     'title' => 'Retreat, Asia, International Tours',
                        // ],
                    ];

                    $walletRouteNames = collect($walletRoutes)->pluck('route')->toArray();
                @endphp

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs($walletRouteNames) ? 'active' : '' }}"
                        href="#sidebarWallet"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs($walletRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarWallet">

                        <i class="las la-wallet"></i>
                        <span data-key="t-authentication">Wallet</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs($walletRouteNames) ? 'show' : '' }}"
                        id="sidebarWallet">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($walletRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ route($item['route']) }}"
                                        class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li> --}}

                <!-- Delivery Report Dropdown -->
                @php
                    $deliveryRoutes = [
                        [
                            'route' => 'user.order-history',
                            'icon' => 'las la-file-alt',
                            'title' => 'Order History',
                        ],
                        [
                            'route' => 'user.by-hand-delivery',
                            'icon' => 'las la-hands-helping',
                            'title' => 'By Hand Delivery List',
                        ],
                        [
                            'route' => 'user.courier-delivery',
                            'icon' => 'las la-truck',
                            'title' => 'Courier Delivery List',
                        ],
                        [
                            'route' => 'user.by-hand-award',
                            'icon' => 'las la-gifts',
                            'title' => 'By Hand T.B.D Award/Reward List',
                        ],
                        [
                            'route' => 'user.by-courier-award',
                            'icon' => 'las la-box-open',
                            'title' => 'By Courier T.B.D Award/Reward List',
                        ],
                        [
                            'route' => 'user.other-products',
                            'icon' => 'las la-boxes',
                            'title' => 'Other Products',
                        ],
                    ];

                    $deliveryRouteNames = collect($deliveryRoutes)->pluck('route')->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs(...$deliveryRouteNames) ? 'active' : '' }}"
                        href="#sidebarDelivery"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs(...$deliveryRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarDelivery">

                        <i class="las la-shipping-fast"></i>
                        <span data-key="t-authentication">Delivery Report</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs(...$deliveryRouteNames) ? 'show' : '' }}"
                        id="sidebarDelivery">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($deliveryRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ route($item['route']) }}"
                                        class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>

                <!-- Withdrawal Dropdown -->
                @php
                    $withdrawalRoutes = [
                        [
                            'route' => 'withdrawal.history',
                            'icon' => 'las la-history',
                            'title' => 'Withdrawal History',
                        ],
                        [
                            'route' => '#',
                            'icon' => 'las la-percentage',
                            'title' => 'Annual Commission T.D.S',
                        ],
                        [
                            'route' => '#',
                            'icon' => 'las la-file-contract',
                            'title' => '194R',
                        ],
                    ];

                    $withdrawalRouteNames = collect($withdrawalRoutes)
                        ->pluck('route')
                        ->filter(fn($route) => $route !== '#')
                        ->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs(...$withdrawalRouteNames) ? 'active' : '' }}"
                        href="#sidebarWithdrawal"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs(...$withdrawalRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarWithdrawal">

                        <i class="las la-money-check-alt"></i>
                        <span data-key="t-authentication">Withdrawal</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs(...$withdrawalRouteNames) ? 'show' : '' }}"
                        id="sidebarWithdrawal">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($withdrawalRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                                        class="nav-link {{ $item['route'] !== '#' && request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>

                <!-- KYC Dropdown -->
                @php
                    $kycRoutes = [
                        [
                            'route' => 'user.kyc',
                            'icon' => 'las la-user-check',
                            'title' => 'KYC',
                        ],
                        [
                            'route' => '#',
                            'icon' => 'las la-file-contract',
                            'title' => 'Admin Documents And Direct Seller Agreement',
                        ],
                    ];

                    $kycRouteNames = collect($kycRoutes)
                        ->pluck('route')
                        ->filter(fn ($route) => $route !== '#')
                        ->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs(...$kycRouteNames) ? 'active' : '' }}"
                        href="#sidebarKyc"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs(...$kycRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarKyc">

                        <i class="las la-id-card-alt"></i>
                        <span data-key="t-authentication">KYC</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs(...$kycRouteNames) ? 'show' : '' }}"
                        id="sidebarKyc">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($kycRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                                        class="nav-link {{ $item['route'] !== '#' && request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>

                <!-- Grievance Cell Dropdown -->
                @php
                    $grievanceRoutes = [
                        [
                            'route' => 'user.grievance.raise-ticket',
                            'icon' => 'las la-ticket-alt',
                            'title' => 'Raise Ticket',
                        ],
                        [
                            'route' => 'user.grievance.inbox',
                            'icon' => 'las la-inbox',
                            'title' => 'Inbox',
                        ],
                        [
                            'route' => 'user.grievance.outbox',
                            'icon' => 'las la-paper-plane',
                            'title' => 'Outbox',
                        ],
                        [
                            'route' => 'user.callback',
                            'icon' => 'las la-phone',
                            'title' => 'Schedule Callback',
                        ],
                    ];

                    $grievanceRouteNames = collect($grievanceRoutes)
                        ->pluck('route')
                        ->toArray();
                @endphp

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs(...$grievanceRouteNames) ? 'active' : '' }}"
                        href="#sidebarGrievance"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs(...$grievanceRouteNames) ? 'true' : 'false' }}"
                        aria-controls="sidebarGrievance">

                        <i class="las la-headset"></i>
                        <span data-key="t-authentication">Grievance Cell</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->routeIs(...$grievanceRouteNames) ? 'show' : '' }}"
                        id="sidebarGrievance">

                        <ul class="nav nav-sm flex-column">
                            @foreach ($grievanceRoutes as $item)
                                <li class="nav-item">
                                    <a href="{{ route($item['route']) }}"
                                        class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </li>
                {{-- <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a class="nav-link menu-link active" href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off fs-15 align-middle me-1 text -danger"></i> 
                            <span key="t-logout">Logout</span>
                        </a> 
                    </form>
                </li> --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
