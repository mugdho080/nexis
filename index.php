<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
$featurePillars = [
    [
        'title' => 'Budget & Fund Management',
        'items' => [
            'Real-time spending snapshots with Core/Capital/Capacity breakdowns.',
            'Spending alerts based on plan duration and burn rate trends.',
            'Fund quarantining tied to service agreements.'
        ]
    ],
    [
        'title' => 'FastPayment Invoice Engine',
        'items' => [
            'OCR ingestion for ABN, GST, line items, and service dates.',
            'NDIS price guide validation and budget availability checks.',
            'Three-tier approval workflows (Auto, Manual, Nominee).'
        ]
    ],
    [
        'title' => 'Operational Reporting',
        'items' => [
            'Statement of account PDFs and remittance advice automation.',
            'Utilization reports for support coordinators.',
            'Audit-ready trails for every approval or edit.'
        ]
    ]
];

$backOffice = [
    [
        'title' => 'NDIA & PACE Integrations',
        'items' => [
            'Bulk claim file generation for PRODA uploads.',
            'Real-time plan sync via PACE APIs with rejection handling.',
            'Error dashboards for budget or pricing exceptions.'
        ]
    ],
    [
        'title' => 'Financial Operations',
        'items' => [
            'ABA file exports for high-volume EFT payments.',
            'Trust account reconciliation and fee management.',
            'ABN verification via ABR lookup.'
        ]
    ],
    [
        'title' => 'Strategic Tools',
        'items' => [
            'Price guide navigator with July 1 updates.',
            'Budget calculator for long-term service schedules.',
            'Predictive spend alerts before plan expiry.'
        ]
    ]
];

$budgetSummary = [
    'total' => 825000,
    'spent' => 412500,
    'quarantined' => 185000
];

$budgetCategories = [
    [
        'name' => 'Core',
        'allocated' => 420000,
        'spent' => 210000,
        'quarantined' => 90000,
        'line_items' => [
            ['Support Item' => 'Assistance with Daily Life', 'amount' => 140000],
            ['Support Item' => 'Social & Community', 'amount' => 70000]
        ]
    ],
    [
        'name' => 'Capital',
        'allocated' => 240000,
        'spent' => 112500,
        'quarantined' => 45000,
        'line_items' => [
            ['Support Item' => 'Assistive Tech', 'amount' => 95000],
            ['Support Item' => 'Home Modifications', 'amount' => 65000]
        ]
    ],
    [
        'name' => 'Capacity Building',
        'allocated' => 165000,
        'spent' => 90000,
        'quarantined' => 50000,
        'line_items' => [
            ['Support Item' => 'Support Coordination', 'amount' => 55000],
            ['Support Item' => 'Improved Daily Living', 'amount' => 35000]
        ]
    ]
];

$spendingAlerts = [
    [
        'participant' => 'NDIS-48291',
        'category' => 'Core',
        'status' => 'Overspend Risk',
        'message' => 'Projected to exhaust funds in 27 days.'
    ],
    [
        'participant' => 'NDIS-91374',
        'category' => 'Capacity Building',
        'status' => 'Underspend Opportunity',
        'message' => '42% of funds remain with 90 days left.'
    ]
];

$quarantineAgreements = [
    [
        'provider' => 'BrightPath Therapy',
        'category' => 'Core',
        'amount' => 50000,
        'service' => 'Daily Living Support',
        'status' => 'Active'
    ],
    [
        'provider' => 'Accessible Homes Co.',
        'category' => 'Capital',
        'amount' => 30000,
        'service' => 'Home Modification Package',
        'status' => 'Active'
    ]
];

$executiveOverview = [
    [
        'label' => 'Total Funds Managed',
        'value' => 18450000,
        'meta' => 'Active plans across all regions',
        'badge' => 'AUD'
    ],
    [
        'label' => 'Processing Queue',
        'value' => 142,
        'meta' => 'OCR, review, approval pipeline',
        'badge' => 'Invoices'
    ],
    [
        'label' => 'Utilization Alerts',
        'value' => 38,
        'meta' => 'Over/under spending flags',
        'badge' => 'Participants'
    ],
    [
        'label' => 'NDIA Rejection Rate',
        'value' => 3.4,
        'meta' => 'Last 30 days',
        'badge' => '%'
    ]
];

$participants = [
    [
        'name' => 'Ava Robinson',
        'ndis' => '431002911',
        'plan_end' => '2024-12-12',
        'pace' => 'Transitioned',
        'alerts' => 'Overspending'
    ],
    [
        'name' => 'Noah Bennett',
        'ndis' => '431009871',
        'plan_end' => '2024-10-03',
        'pace' => 'Pending',
        'alerts' => 'On Track'
    ],
    [
        'name' => 'Mia Patel',
        'ndis' => '431004562',
        'plan_end' => '2024-09-15',
        'pace' => 'Transitioned',
        'alerts' => 'Underspending'
    ],
    [
        'name' => 'Liam Nguyen',
        'ndis' => '431006114',
        'plan_end' => '2025-01-28',
        'pace' => 'Transitioned',
        'alerts' => 'On Track'
    ],
    [
        'name' => 'Sophia Martinez',
        'ndis' => '431008442',
        'plan_end' => '2024-08-22',
        'pace' => 'Pending',
        'alerts' => 'Overspending'
    ]
];

$priceGuide = [
    ['code' => '01_011_0107_1_1', 'description' => 'Assistance with Daily Living', 'rate' => 67.56, 'region' => 'Metro'],
    ['code' => '04_104_0125_6_1', 'description' => 'Assistive Technology Assessment', 'rate' => 193.99, 'region' => 'Metro'],
    ['code' => '07_101_0107_8_3', 'description' => 'Support Coordination', 'rate' => 100.14, 'region' => 'Remote'],
    ['code' => '15_037_0117_1_3', 'description' => 'Improved Daily Living Training', 'rate' => 75.12, 'region' => 'Very Remote']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NDIS Plan Management SaaS Blueprint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
</head>
<body>
    <div class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge text-bg-light mb-3">Plan Management SaaS • Australia</span>
                    <h1 class="display-5 fw-bold">World-class NDIS Plan Management Dashboard</h1>
                    <p class="lead">A production-ready blueprint built for Plan Partners-level automation. Design with raw PHP, MySQL, jQuery, and AJAX—with back-office intelligence and participant-first experiences.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <button class="btn btn-primary btn-lg" id="load-summary">Load Executive Snapshot</button>
                        <button class="btn btn-outline-light btn-lg" id="load-alerts">View Spending Alerts</button>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card shadow-lg mt-4 mt-lg-0">
                        <div class="card-body">
                            <h5 class="card-title">Executive Snapshot</h5>
                            <div id="summary" class="summary-grid">
                                <div>
                                    <span class="label">Participants</span>
                                    <strong>—</strong>
                                </div>
                                <div>
                                    <span class="label">Providers</span>
                                    <strong>—</strong>
                                </div>
                                <div>
                                    <span class="label">Invoices Pending</span>
                                    <strong>—</strong>
                                </div>
                                <div>
                                    <span class="label">Invoices Paid</span>
                                    <strong>—</strong>
                                </div>
                                <div>
                                    <span class="label">Total Budget</span>
                                    <strong>—</strong>
                                </div>
                                <div>
                                    <span class="label">Spent</span>
                                    <strong>—</strong>
                                </div>
                                <div>
                                    <span class="label">Committed</span>
                                    <strong>—</strong>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Live metrics sourced from MySQL or mock data.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h2 class="mb-1">Admin Dashboard</h2>
                        <p class="text-muted mb-0">Mission control for plan management operations.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge text-bg-dark">Admin: admin@admin.com</span>
                        <a href="logout.php" class="btn btn-outline-secondary btn-sm">Log out</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <?php foreach ($executiveOverview as $card): ?>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-uppercase text-muted mb-0"><?= htmlspecialchars($card['label']) ?></h6>
                                <span class="badge text-bg-primary"><?= htmlspecialchars($card['badge']) ?></span>
                            </div>
                            <div class="display-6 fw-semibold mb-1">
                                <?= is_float($card['value']) ? number_format($card['value'], 1) : number_format($card['value']) ?>
                            </div>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($card['meta']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="card-title mb-1">Budget & Fund Management</h5>
                                <p class="text-muted small mb-0">Live snapshot of total plan funding, spend, and quarantined allocations.</p>
                            </div>
                            <span class="badge text-bg-info">Dummy Data</span>
                        </div>
                        <div class="row mt-4 g-3">
                            <div class="col-lg-4">
                                <div class="summary-card" data-total="<?= $budgetSummary['total'] ?>" data-spent="<?= $budgetSummary['spent'] ?>" data-quarantined="<?= $budgetSummary['quarantined'] ?>">
                                    <h6>Total Plan Budget</h6>
                                    <p class="display-6 mb-1">$<?= number_format($budgetSummary['total']) ?></p>
                                    <div class="progress-stack">
                                        <div class="progress">
                                            <div class="progress-bar bg-success spent-bar" role="progressbar"></div>
                                        </div>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-warning quarantine-bar" role="progressbar"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mt-2">
                                        <span>Spent: $<?= number_format($budgetSummary['spent']) ?></span>
                                        <span>Quarantined: $<?= number_format($budgetSummary['quarantined']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row g-3">
                                    <?php foreach ($budgetCategories as $index => $category): ?>
                                        <div class="col-md-4">
                                            <div class="category-card" data-total="<?= $category['allocated'] ?>" data-spent="<?= $category['spent'] ?>" data-quarantined="<?= $category['quarantined'] ?>">
                                                <h6><?= htmlspecialchars($category['name']) ?></h6>
                                                <p class="fw-semibold mb-1">$<?= number_format($category['allocated']) ?> allocated</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success spent-bar" role="progressbar"></div>
                                                    <div class="progress-bar bg-warning quarantine-bar" role="progressbar"></div>
                                                </div>
                                                <button class="btn btn-link p-0 mt-2 small" data-bs-toggle="collapse" data-bs-target="#category-details-<?= $index ?>" aria-expanded="false">View breakdown</button>
                                                <div class="collapse mt-2" id="category-details-<?= $index ?>">
                                                    <ul class="list-group list-group-flush small">
                                                        <?php foreach ($category['line_items'] as $line): ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><?= htmlspecialchars($line['Support Item']) ?></span>
                                                                <span>$<?= number_format($line['amount']) ?></span>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Multi-User Portal Roles</h5>
                        <p class="text-muted">Distinct permissions for participants, coordinators, and providers.</p>
                        <div id="roles" class="role-list"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Front-End Experience</h5>
                        <div class="row">
                            <?php foreach ($featurePillars as $pillar): ?>
                                <div class="col-md-4">
                                    <h6 class="text-uppercase text-primary"><?= htmlspecialchars($pillar['title']) ?></h6>
                                    <ul class="small">
                                        <?php foreach ($pillar['items'] as $item): ?>
                                            <li><?= htmlspecialchars($item) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Back-Office Engine</h5>
                        <div class="row">
                            <?php foreach ($backOffice as $pillar): ?>
                                <div class="col-md-4">
                                    <h6 class="text-uppercase text-secondary"><?= htmlspecialchars($pillar['title']) ?></h6>
                                    <ul class="small">
                                        <?php foreach ($pillar['items'] as $item): ?>
                                            <li><?= htmlspecialchars($item) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Compliance Checklist</h5>
                        <ul class="list-unstyled checklist">
                            <li><span>Data residency in AWS Sydney (ap-southeast-2)</span></li>
                            <li><span>ISO 27001 or NDIA Cyber Security Maturity</span></li>
                            <li><span>Immutable audit logs with timestamped approvals</span></li>
                            <li><span>MFA + AES-256 encryption across portals</span></li>
                        </ul>
                        <div class="alert alert-primary mt-3 mb-0">
                            <strong>Strategic Differentiator:</strong> Natural language search for NDIS eligibility & price guide policy.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Architecture Map</h5>
                        <ol class="small">
                            <li>Client portals (participant, coordinator, provider).</li>
                            <li>Plan manager admin view for staff operations.</li>
                            <li>NDIA integration layer (PRODA + PACE).</li>
                            <li>Data & audit services with encrypted storage.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Launch Roadmap</h5>
                        <div class="timeline">
                            <div>
                                <span class="badge text-bg-primary">Phase 1</span>
                                <p>Core back-office automation: OCR, bulk claiming, ABA payments.</p>
                            </div>
                            <div>
                                <span class="badge text-bg-secondary">Phase 2</span>
                                <p>User portals with traffic-light budgets and approval workflows.</p>
                            </div>
                            <div>
                                <span class="badge text-bg-dark">Phase 3</span>
                                <p>Predictive spend alerts and smart service agreement quarantining.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2" id="spending-alerts-section">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="card-title mb-1">Spending Alerts</h5>
                                <p class="text-muted small mb-0">Automated notifications based on plan burn rate.</p>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" id="run-spending-check">Run spending check</button>
                        </div>
                        <div id="alerts" class="alert-grid mt-3">
                            <?php foreach ($spendingAlerts as $alert): ?>
                                <div class="alert alert-<?= $alert['status'] === 'Overspend Risk' ? 'warning' : 'info' ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong><?= htmlspecialchars($alert['participant']) ?></strong>
                                        <span class="badge text-bg-secondary"><?= htmlspecialchars($alert['category']) ?></span>
                                    </div>
                                    <p class="mb-0"><?= htmlspecialchars($alert['status']) ?> — <?= htmlspecialchars($alert['message']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Fund Quarantining</h5>
                        <p class="text-muted small">Lock budget amounts to service agreements with providers.</p>
                        <form id="quarantine-form" class="row g-2">
                            <div class="col-12">
                                <input type="text" class="form-control" name="provider" placeholder="Provider name" required>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="category" required>
                                    <option value="">Category</option>
                                    <option value="Core">Core</option>
                                    <option value="Capital">Capital</option>
                                    <option value="Capacity Building">Capacity Building</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="amount" placeholder="Amount" min="0" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" name="service" placeholder="Service agreement" required>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Quarantine funds</button>
                            </div>
                        </form>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Active Quarantines</h6>
                                <span class="badge text-bg-warning" id="quarantine-total">$0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Provider</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="quarantine-table">
                                        <?php foreach ($quarantineAgreements as $agreement): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($agreement['provider']) ?></td>
                                                <td><?= htmlspecialchars($agreement['category']) ?></td>
                                                <td data-amount="<?= $agreement['amount'] ?>">$<?= number_format($agreement['amount']) ?></td>
                                                <td><span class="badge text-bg-success"><?= htmlspecialchars($agreement['status']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="card-title mb-1">Participant Management</h5>
                                <p class="text-muted small mb-0">Search the master list and monitor plan expiries.</p>
                            </div>
                            <input type="search" class="form-control form-control-sm w-auto" id="participant-search" placeholder="Search participants">
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Participant</th>
                                        <th>NDIS Number</th>
                                        <th>Plan End</th>
                                        <th>PACE Status</th>
                                        <th>Utilization</th>
                                    </tr>
                                </thead>
                                <tbody id="participant-table">
                                    <?php foreach ($participants as $participant): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($participant['name']) ?></td>
                                            <td><?= htmlspecialchars($participant['ndis']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($participant['plan_end']) ?>
                                                <span class="badge text-bg-warning ms-2">Watchlist</span>
                                            </td>
                                            <td>
                                                <span class="badge text-bg-<?= $participant['pace'] === 'Transitioned' ? 'success' : 'secondary' ?>">
                                                    <?= htmlspecialchars($participant['pace']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($participant['alerts']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mb-0">Watchlist shows plans ending within the next 90 days.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Financial Operations</h5>
                        <p class="text-muted small">High-volume payables and reconciliation workflows.</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary">Generate Bulk Claim File</button>
                            <button class="btn btn-outline-primary">Export ABA Payment File</button>
                            <button class="btn btn-outline-primary">Run Trust Account Reconciliation</button>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            Queue status: 86 approved invoices ready for bulk claiming.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Compliance & Pricing</h5>
                        <p class="text-muted small">NDIS price guide navigator with regional multipliers.</p>
                        <input type="search" class="form-control form-control-sm" id="price-search" placeholder="Search NDIS codes or keywords">
                        <div class="table-responsive mt-3">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Rate (AUD)</th>
                                        <th>Region</th>
                                    </tr>
                                </thead>
                                <tbody id="price-table">
                                    <?php foreach ($priceGuide as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['code']) ?></td>
                                            <td><?= htmlspecialchars($item['description']) ?></td>
                                            <td>$<?= number_format($item['rate'], 2) ?></td>
                                            <td><?= htmlspecialchars($item['region']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge text-bg-secondary">Remote +25%</span>
                            <span class="badge text-bg-secondary">Very Remote +40%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h5 class="card-title mb-1">Live API Preview</h5>
                                <p class="text-muted small mb-0">Inspect the JSON payloads that power the dashboard widgets.</p>
                            </div>
                            <div class="btn-group" role="group" aria-label="API preview endpoints">
                                <button class="btn btn-outline-primary btn-sm preview-btn" data-preview-endpoint="summary">Summary</button>
                                <button class="btn btn-outline-primary btn-sm preview-btn" data-preview-endpoint="alerts">Alerts</button>
                                <button class="btn btn-outline-primary btn-sm preview-btn" data-preview-endpoint="roles">Roles</button>
                            </div>
                        </div>
                        <pre id="api-preview" class="api-preview mt-3">{}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 text-center text-muted">
        <small>Built with raw PHP, MySQL, jQuery, AJAX, and Bootstrap.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/app.js"></script>
</body>
</html>
