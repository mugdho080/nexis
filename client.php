<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
$clientId = $_GET['id'] ?? '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanManager SaaS - Client Overview</title>
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
        <a class="nav-link" href="index.php">Dashboard</a>
        <a class="nav-link active">Participants</a>
        <a class="nav-link d-flex justify-content-between">
            Invoices <span class="badge bg-danger" id="badge-pending">0</span>
        </a>
        <a class="nav-link">Providers</a>
        <a class="nav-link">Settings</a>
    </nav>
</div>

<div class="main">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Participant Overview</h2>
            <p class="text-muted mb-0" id="client-meta">Loading client profile...</p>
        </div>
        <a class="btn btn-outline-secondary" href="index.php">Back to Dashboard</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card stat-card p-4">
                <h5 class="fw-bold">Budget & Fund Snapshot</h5>
                <p class="text-muted small">Total budget vs. spent vs. quarantined.</p>
                <div class="budget-bars" id="budget-snapshot">
                    <div class="budget-row">
                        <span>Total Budget</span>
                        <strong id="budget-total">$0</strong>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" id="budget-spent" style="width: 0%"></div>
                        <div class="progress-bar bg-warning" id="budget-quarantine" style="width: 0%"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Spent: <span id="budget-spent-label">$0</span></span>
                        <span>Quarantined: <span id="budget-quarantine-label">$0</span></span>
                    </div>
                </div>
                <div class="row g-3 mt-3" id="category-cards"></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card stat-card p-4 h-100">
                <h5 class="fw-bold">Spending Alerts</h5>
                <p class="text-muted small">Automated notifications based on plan utilization.</p>
                <div id="client-alerts" class="alert-grid"></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card stat-card p-4 h-100">
                <h5 class="fw-bold">Fund Quarantining</h5>
                <p class="text-muted small">Funds locked to service agreements.</p>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Service</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="quarantine-list"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card stat-card p-4 h-100">
                <h5 class="fw-bold">Assigned Providers</h5>
                <p class="text-muted small">Providers currently linked to this participant.</p>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Service</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="provider-list"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card stat-card p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Client Invoices</h5>
                        <p class="text-muted small mb-0">All submitted invoices for this participant.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Status</th>
                                <th>Service Date</th>
                                <th>Compliance</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="client-invoice-list"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card stat-card p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Invoice Ingestion</h5>
                        <p class="text-muted small mb-0">Upload PDFs for OCR or enter invoices manually.</p>
                    </div>
                    <span class="badge text-bg-info">Dummy Data</span>
                </div>
                <ul class="nav nav-tabs" id="invoiceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="ocr-tab" data-bs-toggle="tab" data-bs-target="#ocr-pane" type="button" role="tab">PDF Upload (OCR)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual-pane" type="button" role="tab">Manual Entry</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="ocr-pane" role="tabpanel">
                        <form id="ocr-form" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Upload Invoice PDF</label>
                                <input type="file" class="form-control" accept="application/pdf">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Provider Email</label>
                                <input type="email" class="form-control" placeholder="billing@provider.com">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-outline-primary" type="button" id="simulate-ocr">Run OCR Simulation</button>
                            </div>
                        </form>
                        <div class="mt-3" id="ocr-preview"></div>
                    </div>
                    <div class="tab-pane fade" id="manual-pane" role="tabpanel">
                        <form id="manual-form" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-control" name="business" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ABN</label>
                                <input type="text" class="form-control" name="abn" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">BSB</label>
                                <input type="text" class="form-control" name="bsb" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" name="account" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" name="invoice_no" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" name="invoice_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Budget Category</label>
                                <select class="form-select" name="budget_category" required>
                                    <option value="">Select</option>
                                    <option>Core</option>
                                    <option>Capital</option>
                                    <option>Capacity Building</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead>
                                            <tr>
                                                <th>Support Item</th>
                                                <th>Service Date</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>GST</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control form-control-sm" placeholder="01_011_0107_1_1" required></td>
                                                <td><input type="date" class="form-control form-control-sm" required></td>
                                                <td><input type="number" class="form-control form-control-sm" value="1" min="1" required></td>
                                                <td><input type="number" class="form-control form-control-sm" value="75" min="0" required></td>
                                                <td><input type="text" class="form-control form-control-sm" value="GST Free"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-warning d-none" id="duplicate-warning"></div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Submit Invoice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="clientInvoiceDetail" aria-labelledby="clientInvoiceDetailLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="clientInvoiceDetailLabel">Invoice Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3">
            <span class="badge bg-secondary" id="client-detail-status"></span>
        </div>
        <p class="mb-1"><strong>Invoice:</strong> <span id="client-detail-invoice"></span></p>
        <p class="mb-1"><strong>Provider:</strong> <span id="client-detail-provider"></span></p>
        <p class="mb-3"><strong>Budget Category:</strong> <span id="client-detail-budget"></span></p>
        <label class="form-label">Update Status</label>
        <select class="form-select mb-4" id="client-detail-status-select">
            <option>Awaiting Approval</option>
            <option>Ready for PRODA</option>
            <option>Paid</option>
        </select>
        <h6>Support Items</h6>
        <ul class="list-group list-group-flush" id="client-detail-items"></ul>
    </div>
</div>

<script>
const clientId = <?php echo json_encode($clientId); ?>;
let knownInvoices = [];
let clientInvoices = [];

function formatCurrency(value) {
    return new Intl.NumberFormat('en-AU', {
        style: 'currency',
        currency: 'AUD',
        maximumFractionDigits: 0
    }).format(value || 0);
}

function renderClient(data) {
    $('#client-meta').text(`${data.name} · NDIS ${data.ndis_no} · Plan ${data.start} → ${data.end}`);
    $('#badge-pending').text(data.pending_invoices);
    knownInvoices = data.existing_invoices || [];
    clientInvoices = data.invoices || [];

    $('#budget-total').text(formatCurrency(data.budget.total));
    $('#budget-spent-label').text(formatCurrency(data.budget.spent));
    $('#budget-quarantine-label').text(formatCurrency(data.budget.quarantined));

    const spentPercent = data.budget.total ? (data.budget.spent / data.budget.total) * 100 : 0;
    const quarantinePercent = data.budget.total ? (data.budget.quarantined / data.budget.total) * 100 : 0;
    $('#budget-spent').css('width', `${Math.min(spentPercent, 100)}%`);
    $('#budget-quarantine').css('width', `${Math.min(quarantinePercent, 100)}%`);

    const categoryHtml = data.categories.map(category => {
        const spent = category.spent / category.allocated * 100;
        const quarantined = category.quarantined / category.allocated * 100;
        return `
            <div class="col-md-4">
                <div class="category-card">
                    <h6>${category.name}</h6>
                    <p class="text-muted small mb-2">${formatCurrency(category.allocated)} allocated</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: ${spent}%"></div>
                        <div class="progress-bar bg-warning" style="width: ${quarantined}%"></div>
                    </div>
                    <button class="btn btn-link p-0 small" data-bs-toggle="collapse" data-bs-target="#cat-${category.key}">View breakdown</button>
                    <div class="collapse mt-2" id="cat-${category.key}">
                        <ul class="list-group list-group-flush small">
                            ${category.items.map(item => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${item.label}</span>
                                    <span>${formatCurrency(item.amount)}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    $('#category-cards').html(categoryHtml);

    const alertsHtml = data.alerts.map(alert => `
        <div class="alert ${alert.type === 'Overspend' ? 'alert-warning' : 'alert-info'}">
            <strong>${alert.type}</strong>
            <p class="mb-0">${alert.message}</p>
        </div>
    `).join('');
    $('#client-alerts').html(alertsHtml || '<p class="text-muted">No alerts.</p>');

    const quarantineHtml = data.quarantines.map(item => `
        <tr>
            <td>${item.provider}</td>
            <td>${item.service}</td>
            <td>${formatCurrency(item.amount)}</td>
        </tr>
    `).join('');
    $('#quarantine-list').html(quarantineHtml || '<tr><td colspan="3" class="text-muted">No quarantined funds.</td></tr>');

    const providerHtml = data.providers.map(provider => `
        <tr>
            <td>${provider.name}</td>
            <td>${provider.service}</td>
            <td><span class="badge ${provider.status === 'Active' ? 'bg-success' : 'bg-secondary'}">${provider.status}</span></td>
        </tr>
    `).join('');
    $('#provider-list').html(providerHtml || '<tr><td colspan="3" class="text-muted">No providers assigned.</td></tr>');

    const invoiceRows = clientInvoices.map(invoice => `
        <tr>
            <td><strong>${invoice.invoice_no}</strong></td>
            <td><span class="badge bg-secondary">${invoice.status}</span></td>
            <td>${invoice.service_date}</td>
            <td><span class="badge ${invoice.compliance === 'OK' ? 'bg-success' : 'bg-danger'}">${invoice.compliance}</span></td>
            <td>${formatCurrency(invoice.total)}</td>
            <td><button class="btn btn-sm btn-outline-primary view-client-invoice" data-invoice="${invoice.invoice_no}">View</button></td>
        </tr>
    `).join('');
    $('#client-invoice-list').html(invoiceRows || '<tr><td colspan="6" class="text-muted">No invoices submitted.</td></tr>');
}

$.get('admin_api.php?action=get_client&id=' + encodeURIComponent(clientId), function (data) {
    renderClient(data);
});

$('#manual-form').on('submit', function (event) {
    event.preventDefault();
    const invoiceNo = $(this).find('[name="invoice_no"]').val().trim();
    const match = knownInvoices.find(item => item.invoice_no === invoiceNo);
    const $warning = $('#duplicate-warning');
    if (match) {
        $warning.removeClass('d-none').html(`Duplicate invoice detected for ${match.provider} (${match.invoice_no}). <a href=\"mailto:${match.email}?subject=Duplicate%20Invoice%20${match.invoice_no}\" class=\"alert-link\">Email provider</a>`);
        return;
    }
    $warning.addClass('d-none').text('');
    alert('Invoice submitted (dummy).');
    this.reset();
});

$('#simulate-ocr').on('click', function () {
    $('#ocr-preview').html(`\n        <div class=\"alert alert-info\">\n            OCR Complete: Extracted ABN 51 824 753 556, Invoice INV-1004, Total $1,250.\n        </div>\n    `);
});

$(document).on('click', '.view-client-invoice', function () {
    const invoiceNo = $(this).data('invoice');
    const invoice = clientInvoices.find(item => item.invoice_no === invoiceNo);
    if (!invoice) {
        return;
    }
    $('#client-detail-status').text(invoice.status);
    $('#client-detail-invoice').text(invoice.invoice_no);
    $('#client-detail-provider').text(invoice.provider);
    $('#client-detail-budget').text(invoice.budget);
    $('#client-detail-status-select').val(invoice.status);

    const items = invoice.support_items.map(item => `
        <li class=\"list-group-item d-flex justify-content-between align-items-center\">
            <span>${item.code} · ${item.description}</span>
            <span>${formatCurrency(item.amount)}</span>
        </li>
    `).join('');
    $('#client-detail-items').html(items);

    const offcanvasElement = document.getElementById('clientInvoiceDetail');
    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvas.show();
});

$('#client-detail-status-select').on('change', function () {
    $('#client-detail-status').text($(this).val());
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
