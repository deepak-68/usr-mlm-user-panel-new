@extends('layouts.master')
@section('title', 'Grievance Outbox')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-paper-plane text-primary me-2"></i>GRIEVANCE OUTBOX
                            <small class="text-muted fs-14 ms-2">(Tickets raised by you)</small>
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Toast -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="mainToast" class="toast align-items-center text-white border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <!-- Raise Ticket Button -->
            <div class="mb-3">
                <a href="{{ route('user.grievance.raise-ticket') }}" class="btn btn-primary" style="background: #1e3a5f; border: none;">
                    <i class="las la-plus-circle me-1"></i> Raise New Ticket
                </a>
            </div>

            <!-- Filter -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Filter by Status</label>
                                <select name="status" id="statusFilter" class="form-select">
                                    <option value="">All</option>
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="outboxTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Ticket No</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Date Raised</th>
                                    <th class="text-center">Status</th>
                                    <th>Admin Reply</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── Ticket Detail Modal ──────────────────────────────────────── --}}
<div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title" id="modalTicketTitle">Ticket</h5>
                    <small class="text-muted" id="modalTicketSubject"></small>
                </div>
                <span id="modalTicketStatus"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="ticketMessagesBody">
                <div class="text-center text-muted py-5">
                    <i class="las la-spinner la-spin la-2x"></i>
                    <p class="mt-2">Loading messages...</p>
                </div>
            </div>
            <div class="modal-footer" id="ticketReplyFooter">
                <form id="replyForm" class="w-100" enctype="multipart/form-data">
                    <input type="hidden" name="ticket_id" id="replyTicketId">
                    <div class="mb-2">
                        <textarea name="message" id="replyMessage" class="form-control" rows="2"
                                  placeholder="Type your reply..." required></textarea>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" name="attachment" id="replyAttachment"
                               class="form-control form-control-sm" style="max-width:200px"
                               accept=".jpg,.jpeg,.png,.pdf">
                        <button type="submit" class="btn btn-primary btn-sm" id="replySubmitBtn"
                                style="background:#1e3a5f;border:none;">
                            <i class="las la-paper-plane me-1"></i> Send
                        </button>
                    </div>
                    <small class="text-muted">Allowed: jpg, jpeg, png, pdf (max 5MB)</small>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
let dataTable;

const statusBadge = {
    open:        '<span class="badge bg-primary">Open</span>',
    in_progress: '<span class="badge bg-warning text-dark">In Progress</span>',
    resolved:    '<span class="badge bg-success">Resolved</span>',
    closed:      '<span class="badge bg-secondary">Closed</span>',
};

const priorityBadge = {
    low:    '<span class="badge bg-info text-dark">Low</span>',
    medium: '<span class="badge bg-warning text-dark">Medium</span>',
    high:   '<span class="badge bg-danger">High</span>',
};

function showToast(message, type = 'success') {
    const toast = document.getElementById('mainToast');
    const toastMessage = document.getElementById('toastMessage');
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
    toast.classList.add(type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-warning');
    toastMessage.textContent = message;
    new bootstrap.Toast(toast, { delay: 4000 }).show();
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-IN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function loadOutbox(status = '') {
    dataTable.clear().draw();

    fetch(`{{ route('user.grievance.outbox.data') }}?status=${status}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data && data.data.length > 0) {
            data.data.forEach((item, index) => {
                const adminReply = item.admin_reply
                    ? `<span title="${item.admin_reply}">${item.admin_reply.substring(0, 40)}${item.admin_reply.length > 40 ? '...' : ''}</span>`
                    : '<span class="text-muted">—</span>';

                dataTable.row.add([
                    index + 1,
                    `<span class="badge bg-light text-dark fw-bold">${item.ticket_no ?? ('#' + item.id)}</span>`,
                    item.subject ?? 'N/A',
                    item.category ?? 'N/A',
                    priorityBadge[item.priority] ?? item.priority ?? 'N/A',
                    formatDate(item.created_at),
                    statusBadge[item.status] ?? `<span class="badge bg-secondary">${item.status ?? 'N/A'}</span>`,
                    // adminReply,
                    `<button type="button" class="btn btn-primary view-messages"  data-id="${item.id}">View Message</button>`,
                ]);
            });
            dataTable.draw();
        } else if (!data.success) {
            showToast(data.message || 'Failed to load tickets.', 'error');
        }
    })
    .catch(() => showToast('Failed to load data. Please try again.', 'error'));
}

document.addEventListener('DOMContentLoaded', function () {
    dataTable = $('#outboxTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: { emptyTable: 'No tickets raised yet.' },
        columnDefs: [{ orderable: false, targets: [6, 7] }],
    });

    loadOutbox();
});

document.getElementById('filterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    loadOutbox(document.getElementById('statusFilter').value);
});

// ── Load ticket messages ──────────────────────────────────────────
function loadTicketMessages(ticketId) {
    document.getElementById('ticketMessagesBody').innerHTML =
        '<div class="text-center text-muted py-5"><i class="las la-spinner la-spin la-2x"></i><p class="mt-2">Loading messages...</p></div>';

    fetch(`{{ url('grievance/ticket-messages') }}/${ticketId}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.status) {
            document.getElementById('ticketMessagesBody').innerHTML =
                `<div class="alert alert-danger">${data.message || 'Failed to load messages.'}</div>`;
            return;
        }

        const ticket = data.ticket;
        document.getElementById('modalTicketTitle').textContent = `Ticket #${ticket.ticket_no}`;
        document.getElementById('modalTicketSubject').textContent = ticket.subject;
        document.getElementById('modalTicketStatus').innerHTML =
            statusBadge[ticket.status] || `<span class="badge bg-secondary">${ticket.status}</span>`;

        document.getElementById('ticketReplyFooter').style.display =
            ticket.status === 'closed' ? 'none' : '';

        if (!data.messages || data.messages.length === 0) {
            document.getElementById('ticketMessagesBody').innerHTML =
                '<div class="text-center text-muted py-4">No messages yet.</div>';
            return;
        }

        let html = '<div class="chat-thread">';
        data.messages.forEach(msg => {
            const align = msg.is_user ? 'text-end' : 'text-start';
            const bg    = msg.is_user ? 'bg-primary text-white' : 'bg-light';
            const name  = msg.sender_name || (msg.is_user ? 'You' : 'Support');
            const time  = msg.created_at || '';

            let attachmentsHtml = '';
            if (msg.attachments && msg.attachments.length > 0) {
                attachmentsHtml = msg.attachments.map(a =>
                    `<br><a href="${a.url}" target="_blank" class="small ${msg.is_user ? 'text-white' : ''}">
                        <i class="las la-paperclip"></i> View Attachment</a>`
                ).join('');
            }

            html += `
                <div class="mb-3 ${align}">
                    <div class="d-inline-block ${bg} rounded-3 px-3 py-2" style="max-width:80%;text-align:left;">
                        <small class="fw-bold d-block ${msg.is_user ? 'text-white-50' : 'text-muted'}">${name}</small>
                        <p class="mb-1">${msg.message}</p>
                        ${attachmentsHtml}
                        <small class="d-block ${msg.is_user ? 'text-white-50' : 'text-muted'}" style="font-size:11px;">${time}</small>
                    </div>
                </div>`;
        });
        html += '</div>';
        document.getElementById('ticketMessagesBody').innerHTML = html;
    })
    .catch(() => {
        document.getElementById('ticketMessagesBody').innerHTML =
            '<div class="alert alert-danger">Failed to load messages. Please try again.</div>';
    });
}

// ── Open ticket detail modal ──────────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-messages');
    if (!btn) return;

    const ticketId = btn.dataset.id;
    document.getElementById('replyTicketId').value = ticketId;
    document.getElementById('replyMessage').value = '';
    document.getElementById('replyAttachment').value = '';

    const modal = new bootstrap.Modal(document.getElementById('ticketModal'));
    modal.show();

    loadTicketMessages(ticketId);
});

// ── Submit reply ──────────────────────────────────────────────────
document.getElementById('replyForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const submitBtn = document.getElementById('replySubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Sending...';

    const formData = new FormData(this);

    fetch(`{{ route('user.grievance.reply-ticket') }}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Reply sent successfully');
            document.getElementById('replyMessage').value = '';
            document.getElementById('replyAttachment').value = '';
            loadTicketMessages(document.getElementById('replyTicketId').value);
        } else {
            showToast(data.message || 'Failed to send reply.', 'error');
        }
    })
    .catch(() => showToast('Failed to send reply. Please try again.', 'error'))
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="las la-paper-plane me-1"></i> Send';
    });
});
</script>
@endpush
