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
</div>

<script>
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
}

$('#refresh-data').on('click', loadData);
$('#participant-search').on('input', function () {
    const term = $(this).val().toLowerCase();
    $('#participant-list tr').each(function () {
        const text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(term) !== -1);
    });
});

$(document).ready(function() {
    loadData();
});
</script>
</body>
</html>
