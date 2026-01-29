<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanManager SaaS - Admin HUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="admin-body">
<div class="sidebar">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h4 class="fw-bold mb-0">PlanManager</h4>
        <a href="logout.php" class="btn btn-sm btn-outline-light">Log out</a>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link active">Dashboard</a>
        <a class="nav-link">Participants</a>
        <a class="nav-link d-flex justify-content-between">
            Invoices <span class="badge bg-danger" id="badge-pending">0</span>
        </a>
        <a class="nav-link">Providers</a>
        <a class="nav-link">Settings</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold">Overview</h2>
            <p class="text-muted mb-0">Mission control for plan management operations.</p>
        </div>
        <button class="btn btn-outline-primary shadow-sm" id="refresh-data">🔄 Refresh Data</button>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stat-card p-4 shadow-sm border-start border-primary border-4">
                <p class="text-muted small mb-1">Total Managed Funds</p>
                <h3 class="fw-bold" id="stat-funds">$0.00</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-4 shadow-sm border-start border-info border-4">
                <p class="text-muted small mb-1">Pending Invoices</p>
                <h3 class="fw-bold" id="stat-pending">0</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-4 shadow-sm border-start border-warning border-4">
                <p class="text-muted small mb-1">Expiring Plans (30d)</p>
                <h3 class="fw-bold" id="stat-expiring">0</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-4 shadow-sm border-start border-danger border-4">
                <p class="text-muted small mb-1">Compliance Flags</p>
                <h3 class="fw-bold" id="stat-flags">0</h3>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Active Participant Oversight</h5>
                <small class="text-muted">Dummy client list with utilization health.</small>
            </div>
            <input type="search" class="form-control form-control-sm w-auto" id="participant-search" placeholder="Search participants">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="ps-4">Client</th>
                        <th>NDIS Number</th>
                        <th>Utilization</th>
                        <th>System</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="participant-list">
                    <tr><td colspan="5" class="text-center p-5">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card shadow-sm border-0 rounded-3 mt-4">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="mb-1 fw-bold">Invoices (All Clients)</h5>
                    <small class="text-muted">Search by client, NDIS number, date, invoice number, or status.</small>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <input type="text" class="form-control form-control-sm" id="invoice-search-name" placeholder="Client or provider">
                    <input type="text" class="form-control form-control-sm" id="invoice-search-ndis" placeholder="NDIS #">
                    <input type="text" class="form-control form-control-sm" id="invoice-search-number" placeholder="Invoice #">
                    <input type="date" class="form-control form-control-sm" id="invoice-search-date">
                    <select class="form-select form-select-sm" id="invoice-search-status">
                        <option value="">All Statuses</option>
                        <option>Awaiting Approval</option>
                        <option>Ready for PRODA</option>
                        <option>Paid</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="ps-4">Invoice</th>
                        <th>Participant</th>
                        <th>Provider</th>
                        <th>Service Date</th>
                        <th>Status</th>
                        <th>Compliance</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="invoice-list">
                    <tr><td colspan="8" class="text-center p-5">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="invoiceDetail" aria-labelledby="invoiceDetailLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="invoiceDetailLabel">Invoice Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3">
            <span class="badge bg-secondary" id="detail-status"></span>
        </div>
        <p class="mb-1"><strong>Invoice:</strong> <span id="detail-invoice"></span></p>
        <p class="mb-1"><strong>Participant:</strong> <span id="detail-participant"></span></p>
        <p class="mb-3"><strong>Provider:</strong> <span id="detail-provider"></span></p>
        <label class="form-label">Update Status</label>
        <select class="form-select mb-4" id="detail-status-select">
            <option>Awaiting Approval</option>
            <option>Ready for PRODA</option>
            <option>Paid</option>
        </select>
        <h6>Support Items</h6>
        <ul class="list-group list-group-flush" id="detail-items"></ul>
    </div>
</div>

<script>
let invoiceData = [];

function loadData() {
    $.get('admin_api.php?action=get_stats', function(data) {
        $('#stat-funds').text('$' + Number(data.total_managed).toLocaleString(undefined, {minimumFractionDigits: 2}));
        $('#stat-pending').text(data.pending_invoices);
        $('#badge-pending').text(data.pending_invoices);
        $('#stat-expiring').text(data.expiring_plans);
        $('#stat-flags').text(data.compliance_flags);
    });

    $.get('admin_api.php?action=get_participants', function(data) {
        let html = '';
        if (!data.length) {
            html = '<tr><td colspan="5" class="text-center p-4">No data. Dummy list unavailable.</td></tr>';
        } else {
            data.forEach(p => {
                let color = p.utilization > 85 ? 'bg-danger' : (p.utilization < 30 ? 'bg-warning' : 'bg-success');
                html += `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${p.name}</div>
                            <small class="text-muted">Plan: ${p.start} to ${p.end}</small>
                        </td>
                        <td><code>${p.ndis_no}</code></td>
                        <td style="width: 220px">
                            <div class="d-flex align-items-center">
                                <span class="me-2 small fw-bold">${p.utilization}%</span>
                                <div class="progress w-100 progress-bar-custom bg-light">
                                    <div class="progress-bar ${color}" style="width: ${p.utilization}%"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge ${p.plan_type === 'PACE' ? 'bg-info text-dark' : 'bg-secondary'}">${p.plan_type}</span></td>
                        <td><a class="btn btn-sm btn-primary" href="client.php?id=${p.ndis_no}">Manage</a></td>
                    </tr>
                `;
            });
        }
        $('#participant-list').html(html);
    });

    $.get('admin_api.php?action=get_invoices', function(data) {
        invoiceData = data || [];
        renderInvoices();
    });
}

function renderInvoices() {
    const nameTerm = $('#invoice-search-name').val().toLowerCase();
    const ndisTerm = $('#invoice-search-ndis').val().toLowerCase();
    const numberTerm = $('#invoice-search-number').val().toLowerCase();
    const dateTerm = $('#invoice-search-date').val();
    const statusTerm = $('#invoice-search-status').val();

    const filtered = invoiceData.filter(invoice => {
        const matchesName = !nameTerm || invoice.participant.toLowerCase().includes(nameTerm) || invoice.provider.toLowerCase().includes(nameTerm);
        const matchesNdis = !ndisTerm || invoice.ndis_no.toLowerCase().includes(ndisTerm);
        const matchesNumber = !numberTerm || invoice.invoice_no.toLowerCase().includes(numberTerm);
        const matchesDate = !dateTerm || invoice.service_date === dateTerm;
        const matchesStatus = !statusTerm || invoice.status === statusTerm;
        return matchesName && matchesNdis && matchesNumber && matchesDate && matchesStatus;
    });

    let rows = '';
    if (!filtered.length) {
        rows = '<tr><td colspan="8" class="text-center p-4">No invoices match the filters.</td></tr>';
    } else {
        filtered.forEach(invoice => {
            rows += `
                <tr>
                    <td class="ps-4"><strong>${invoice.invoice_no}</strong></td>
                    <td>${invoice.participant}<br><small class="text-muted">${invoice.ndis_no}</small></td>
                    <td>${invoice.provider}</td>
                    <td>${invoice.service_date}</td>
                    <td><span class="badge bg-secondary">${invoice.status}</span></td>
                    <td><span class="badge ${invoice.compliance === 'OK' ? 'bg-success' : 'bg-danger'}">${invoice.compliance}</span></td>
                    <td>$${invoice.total.toLocaleString()}</td>
                    <td><button class="btn btn-sm btn-outline-primary view-invoice" data-invoice="${invoice.invoice_no}">View</button></td>
                </tr>
            `;
        });
    }
    $('#invoice-list').html(rows);
}

function showInvoiceDetail(invoiceNo) {
    const invoice = invoiceData.find(item => item.invoice_no === invoiceNo);
    if (!invoice) {
        return;
    }
    $('#detail-status').text(invoice.status);
    $('#detail-invoice').text(invoice.invoice_no);
    $('#detail-participant').text(`${invoice.participant} · ${invoice.ndis_no}`);
    $('#detail-provider').text(invoice.provider);
    $('#detail-status-select').val(invoice.status);

    const items = invoice.support_items.map(item => `
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>${item.code} · ${item.description}</span>
            <span>$${item.amount.toLocaleString()}</span>
        </li>
    `).join('');
    $('#detail-items').html(items);
}

$('#refresh-data').on('click', loadData);
$('#participant-search').on('input', function () {
    const term = $(this).val().toLowerCase();
    $('#participant-list tr').each(function () {
        const text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(term) !== -1);
    });
});

$('#invoice-search-name, #invoice-search-ndis, #invoice-search-number, #invoice-search-date, #invoice-search-status').on('input change', function () {
    renderInvoices();
});

$(document).on('click', '.view-invoice', function () {
    const invoiceNo = $(this).data('invoice');
    showInvoiceDetail(invoiceNo);
    const offcanvasElement = document.getElementById('invoiceDetail');
    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvas.show();
});

$('#detail-status-select').on('change', function () {
    $('#detail-status').text($(this).val());
});

$(document).ready(function() {
    loadData();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
