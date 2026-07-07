@extends('layouts.master')

@section("title", "My Cards")

@section("content")
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4 no-print">
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="las la-id-card me-2"></i>My Cards</h4>
                </div>
            </div>

            <div class="row justify-content-center g-4">

                {{-- ─── ID CARD ─── --}}
                <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center">
                    <div class="mb-3 no-print">
                        <div class="btn-group" role="group">
                            <div class="btn-group" role="group">
                                <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="las la-download me-1"></i>Download
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="downloadImage('id-card')"><i class="las la-image me-2"></i>As Image</button></li>
                                    <li><button class="dropdown-item" onclick="downloadPdf('id-card')"><i class="las la-file-pdf me-2"></i>As PDF</button></li>
                                </ul>
                            </div>
                            <button class="btn btn-info" onclick="shareCard('id-card')" title="Share ID Card"><i class="mdi mdi-share-variant"></i></button>
                            <button class="btn btn-secondary" onclick="printCard('id-card')" title="Print"><i class="las la-print"></i></button>
                        </div>
                    </div>

                    <div id="id-card" class="id-card">
                        <div class="id-header">
                            <div class="id-logo" style="background-image: url('{{ asset('assets/images/logo.webp') }}')"></div>
                            <div class="id-company">VSR MLM NETWORK</div>
                            <div class="id-company-sub">WELLNESS</div>
                        </div>

                        <div class="id-body">
                            <div class="id-photo-box">
                                <div class="id-photo" style="background-image: url('{{ $profileImage ?: asset('assets/images/avatar.png') }}')"></div>
                            </div>

                            <div class="id-name">{{ $user->first_name }} {{ $user->last_name }}</div>

                            <div class="id-username">
                                <i class="las la-user"></i> {{ $user->user_name }}
                            </div>

                            <div class="id-role">Independent Nutritionist &amp; Dietician Consultant</div>

                            <div class="id-line"></div>

                            <div class="id-info">
                                <div class="id-info-item">
                                    <span class="id-info-label">Member ID</span>
                                    <span class="id-info-value">{{ $user->track_id ?? $user->user_name }}</span>
                                </div>
                                <div class="id-info-item">
                                    <span class="id-info-label">Phone</span>
                                    <span class="id-info-value">+91-{{ $user->phone }}</span>
                                </div>
                                <div class="id-info-item">
                                    <span class="id-info-label">Membership</span>
                                    <span class="id-info-value">{{ $user->membership_type ?? 'Member' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="id-footer">
                            Heaven of Wellness
                        </div>
                    </div>
                </div>

                {{-- ─── VISITING CARD ─── --}}
                <div class="col-lg-7 col-md-6 d-flex flex-column align-items-center">
                    <div class="mb-3 no-print">
                        <div class="btn-group" role="group">
                            <div class="btn-group" role="group">
                                <button class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="las la-download me-1"></i>Download
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="downloadImage('visiting-card')"><i class="las la-image me-2"></i>As Image</button></li>
                                    <li><button class="dropdown-item" onclick="downloadPdf('visiting-card')"><i class="las la-file-pdf me-2"></i>As PDF</button></li>
                                </ul>
                            </div>
                            <button class="btn btn-info" onclick="shareCard('visiting-card')" title="Share Visiting Card"><i class="mdi mdi-share-variant"></i></button>
                            <button class="btn btn-secondary" onclick="printCard('visiting-card')" title="Print"><i class="las la-print"></i></button>
                        </div>
                    </div>

                    <div id="visiting-card" class="vc-card">
                        <div class="vc-left">
                            <div class="vc-brand">
                                <div class="vc-logo-wrapper">
                                    <div class="vc-logo" style="background-image: url('{{ asset('assets/images/logo.webp') }}')"></div>
                                </div>
                                <div class="vc-brand-name">VSR MLM NETWORK</div>
                                <div class="vc-brand-sub">WELLNESS</div>
                                <div class="vc-tagline">Heaven of Wellness</div>
                            </div>
                        </div>
                        <div class="vc-right">
                            <div class="vc-user-name">{{ strtoupper($user->first_name . ' ' . $user->last_name) }}</div>
                            <div class="vc-username">{{ $user->user_name }}</div>
                            <div class="vc-title">Independent Nutritionist &amp; Dietician Consultant</div>
                            <div class="vc-divider"></div>
                            <div class="vc-info">
                                <span class="vc-icon"><i class="las la-phone"></i></span>
                                <span class="vc-text">+91-{{ $user->phone }}</span>
                            </div>
                            <div class="vc-info">
                                <span class="vc-icon"><i class="las la-envelope"></i></span>
                                <span class="vc-text">{{ $user->email }}</span>
                            </div>
                            <div class="vc-info">
                                <span class="vc-icon"><i class="las la-globe"></i></span>
                                <span class="vc-text">www.vsrmlmnetworkwellness.com</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
:root {
    --card-primary: var(--in-vertical-menu-item-active-bgcolor, #1e3a8a);
}

/* ═══════════════════════════════════════════
   ID CARD
   ═══════════════════════════════════════════ */
.id-card {
    width: 100%;
    max-width: 320px;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    background: #fff;
}

.id-header {
    background: var(--card-primary);
    padding: 22px 20px 55px;
    text-align: center;
    color: #fff;
}

.id-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    background-color: rgba(255,255,255,0.2);
    margin: 0 auto 6px;
}

.id-company {
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.3;
}

.id-company-sub {
    font-size: 10px;
    letter-spacing: 4px;
    text-transform: uppercase;
    opacity: 0.9;
}

.id-body {
    padding: 0 24px 16px;
    text-align: center;
    border: 1px solid rgba(0,0,0,0.08);
    border-top: none;
    border-bottom: none;
}

.id-photo-box {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid #fff;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    background: #f5f5f5;
    margin: -45px auto 0;
    position: relative;
    z-index: 2;
}

.id-photo {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.id-name {
    font-size: 19px;
    font-weight: 700;
    color: #1a1a2e;
    margin-top: 10px;
    margin-bottom: 2px;
    letter-spacing: 0.3px;
}

.id-username {
    font-size: 11px;
    color: var(--card-primary);
    font-weight: 600;
    margin-bottom: 2px;
}

.id-username i {
    font-size: 12px;
}

.id-role {
    font-size: 10px;
    color: #999;
    margin-bottom: 14px;
    line-height: 1.3;
}

.id-line {
    width: 50px;
    height: 2px;
    background: var(--card-primary);
    margin: 0 auto 14px;
    border-radius: 2px;
    opacity: 0.4;
}

.id-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: #f8f9fc;
    border-radius: 10px;
    overflow: hidden;
}

.id-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 14px;
    background: #fff;
}

.id-info-item + .id-info-item {
    border-top: 1px solid #f0f2f7;
}

.id-info-label {
    font-size: 10px;
    color: #aaa;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.id-info-value {
    font-size: 12px;
    color: #333;
    font-weight: 600;
}

.id-footer {
    padding: 10px 20px;
    text-align: center;
    background: var(--card-primary);
    color: rgba(255,255,255,0.85);
    font-size: 10px;
    font-style: italic;
    letter-spacing: 0.5px;
}

/* ═══════════════════════════════════════════
   VISITING CARD
   ═══════════════════════════════════════════ */
.vc-card {
    display: flex;
    width: 100%;
    max-width: 500px;
    height: 280px;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    background: #fff;
}

.vc-left {
    width: 30%;
    background: var(--card-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 12px;
}

.vc-brand {
    text-align: center;
    color: #fff;
}

.vc-logo-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.vc-logo {
    width: 100%;
    height: 100%;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
}

.vc-brand-name {
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.2;
    margin-bottom: 1px;
}

.vc-brand-sub {
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    opacity: 0.9;
    margin-bottom: 4px;
}

.vc-tagline {
    font-size: 10px;
    font-style: italic;
    opacity: 0.8;
}

.vc-right {
    width: 70%;
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border: 1px solid rgba(0,0,0,0.08);
    border-left: none;
}

.vc-user-name {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a2e;
    letter-spacing: 1px;
    margin-bottom: 1px;
}

.vc-username {
    font-size: 11px;
    color: var(--card-primary);
    font-weight: 600;
    margin-bottom: 4px;
}

.vc-title {
    font-size: 10px;
    color: #888;
    margin-bottom: 12px;
    line-height: 1.4;
}

.vc-divider {
    height: 2px;
    width: 45px;
    background: var(--card-primary);
    margin-bottom: 12px;
    border-radius: 2px;
}

.vc-info {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}

.vc-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: var(--card-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.vc-text {
    font-size: 12px;
    color: #555;
    word-break: break-all;
}

/* ═══════════════════════════════════════════
   PRINT
   ═══════════════════════════════════════════ */
@media print {
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    body * { visibility: hidden !important; }
    .no-print { display: none !important; }
    #print-card, #print-card * { visibility: visible !important; }
    #print-card {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        transform: none !important;
        box-shadow: none !important;
        margin: 10px !important;
    }

    /* Prevent responsive mobile rules from breaking print layout */
    #print-card.vc-card {
        flex-direction: row !important;
        height: 280px !important;
        max-width: 500px !important;
    }
    #print-card.vc-card .vc-left {
        width: 30% !important;
        padding: 20px 12px !important;
    }
    #print-card.vc-card .vc-right {
        width: 70% !important;
        padding: 24px 28px !important;
    }
    @page { size: auto; margin: 0mm; }
}

/* ═══════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════ */
@media (max-width: 768px) {
    .vc-card {
        flex-direction: column;
        height: auto;
        max-width: 340px;
    }
    .vc-left, .vc-right { width: 100%; }
    .vc-left { padding: 20px 15px; }
    .vc-right { padding: 20px 24px; }
    .vc-logo-wrapper { width: 65px; height: 65px; }
    .vc-brand-name { font-size: 14px; }
    .vc-user-name { font-size: 17px; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const userName = '{{ $user->user_name }}';

function printCard(type) {
    const el = document.getElementById(type);
    const rect = el.getBoundingClientRect();
    const clone = el.cloneNode(true);
    clone.id = 'print-card';
    clone.style.width = rect.width + 'px';
    clone.style.height = rect.height + 'px';
    clone.style.flexShrink = '0';
    document.body.appendChild(clone);
    setTimeout(function() {
        window.print();
        setTimeout(function() {
            document.getElementById('print-card')?.remove();
        }, 300);
    }, 200);
}

const h2cOpts = { scale: 2, useCORS: true, backgroundColor: null };

function downloadImage(type) {
    const el = document.getElementById(type);
    html2canvas(el, h2cOpts).then(function(canvas) {
        const link = document.createElement('a');
        link.download = type + '-' + userName + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}

function downloadPdf(type) {
    const el = document.getElementById(type);
    html2canvas(el, h2cOpts).then(function(canvas) {
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const pageW = 210;
        const pageH = 297;
        const margin = 10;
        const maxW = pageW - margin * 2;
        const maxH = pageH - margin * 2;
        const imgW = canvas.width;
        const imgH = canvas.height;
        let pdfW, pdfH;
        if (imgW / imgH > maxW / maxH) {
            pdfW = maxW;
            pdfH = imgH * maxW / imgW;
        } else {
            pdfH = maxH;
            pdfW = imgW * maxH / imgH;
        }
        const pdf = new jsPDF('p', 'mm', 'a4');
        pdf.addImage(imgData, 'PNG', margin + (maxW - pdfW) / 2, margin + (maxH - pdfH) / 2, pdfW, pdfH);
        pdf.save(type + '-' + userName + '.pdf');
    });
}

function shareCard(type) {
    const el = document.getElementById(type);
    html2canvas(el, h2cOpts).then(function(canvas) {
        canvas.toBlob(function(blob) {
            const label = type === 'id-card' ? 'ID Card' : 'Visiting Card';
            const file = new File([blob], type + '-' + userName + '.png', { type: 'image/png' });
            if (navigator.share && navigator.canShare({ files: [file] })) {
                navigator.share({
                    title: label + ' - ' + userName,
                    text: 'My VSR MLM NETWORK ' + label,
                    files: [file]
                }).catch(function() {});
            } else {
                const link = document.createElement('a');
                link.download = file.name;
                link.href = URL.createObjectURL(blob);
                link.click();
                URL.revokeObjectURL(link.href);
                if (typeof showToast === 'function') {
                    showToast('Sharing not supported on this browser. Image was downloaded.', 'warning');
                }
            }
        });
    });
}
</script>
@endsection
