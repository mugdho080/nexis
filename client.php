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
</div>

<script>
const clientId = <?php echo json_encode($clientId); ?>;

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
}

$.get('admin_api.php?action=get_client&id=' + encodeURIComponent(clientId), function (data) {
    renderClient(data);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
