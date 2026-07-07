@extends('layouts.master')

@section("title", "Visiting Card")

@section("content")
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Download Button -->
            <div class="row mb-4 no-print">
                <div class="col-12 text-end">
                    <button onclick="downloadCard()" class="btn btn-success me-2">
                        <i class="las la-download me-2"></i>Download
                    </button>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="las la-print me-2"></i>Print
                    </button>
                </div>
            </div>

            <!-- Visiting Card -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div id="visitingCard">
                        <div class="visiting-card">
                            <!-- Left Panel: Brand -->
                            <div class="card-left">
                                <div class="brand-content">
                                    <div class="logo-wrapper">
                                        <img src="{{ asset('assets/images/logo.webp') }}"
                                             alt="VSR MLM NETWORK"
                                             class="company-logo"
                                             onerror="this.style.display='none'">
                                    </div>
                                    <div class="brand-name">VSR MLM NETWORK</div>
                                    <div class="brand-sub">WELLNESS</div>
                                    <div class="brand-tagline">Heaven of Wellness</div>
                                </div>
                            </div>

                            <!-- Right Panel: User Info -->
                            <div class="card-right">
                                <div class="user-name">{{ strtoupper($user->first_name . ' ' . $user->last_name) }}</div>
                                <div class="user-title">Independent Nutritionist &amp; Dietician Consultant</div>
                                <div class="divider"></div>
                                <div class="info-row">
                                    <span class="info-icon"><i class="las la-phone"></i></span>
                                    <span class="info-text">+91-{{ $user->phone }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-icon"><i class="las la-envelope"></i></span>
                                    <span class="info-text">{{ $user->email }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-icon"><i class="las la-globe"></i></span>
                                    <span class="info-text">www.vsrmlmnetworkwellness.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
#visitingCard {
    padding: 20px;
}

.visiting-card {
    display: flex;
    width: 100%;
    max-width: 600px;
    height: 320px;
    margin: 0 auto;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    position: relative;
}

/* ── Left Panel ── */
.card-left {
    width: 32%;
    background: var(--in-vertical-menu-item-active-bgcolor, #1e3a8a);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 15px;
}

.brand-content {
    text-align: center;
    color: #fff;
}

.logo-wrapper {
    width: 100px;
    height: 100px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.company-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.brand-name {
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.2;
    margin-bottom: 2px;
}

.brand-sub {
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    opacity: 0.9;
    margin-bottom: 6px;
}

.brand-tagline {
    font-size: 11px;
    font-style: italic;
    opacity: 0.8;
}

/* ── Right Panel ── */
.card-right {
    width: 68%;
    padding: 28px 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: #fff;
}

.user-name {
    font-size: 22px;
    font-weight: 700;
    color: #1e3a8a;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.user-title {
    font-size: 11px;
    color: #888;
    margin-bottom: 14px;
    line-height: 1.4;
}

.divider {
    height: 2px;
    width: 50px;
    background: var(--in-vertical-menu-item-active-bgcolor, #1e3a8a);
    margin-bottom: 14px;
    border-radius: 2px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.info-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--in-vertical-menu-item-active-bgcolor, #1e3a8a);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

.info-text {
    font-size: 12px;
    color: #555;
    word-break: break-all;
}

/* ── Print ── */
@media print {
    body * { visibility: hidden; }
    #visitingCard, #visitingCard * { visibility: visible; }
    #visitingCard {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
    .no-print { display: none !important; }
    @page { size: landscape; margin: 10mm; }
}

/* ── Responsive ── */
@media (max-width: 576px) {
    .visiting-card {
        flex-direction: column;
        height: auto;
        max-width: 340px;
    }
    .card-left, .card-right { width: 100%; }
    .card-left { padding: 24px 15px; }
    .card-right { padding: 24px 20px; }
    .logo-wrapper { width: 80px; height: 80px; }
    .brand-name { font-size: 16px; }
    .user-name { font-size: 18px; }
}
</style>

<script>
function downloadCard() {
    window.print();
}
</script>
@endsection
