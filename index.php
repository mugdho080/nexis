<?php
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

        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Spending Alerts</h5>
                        <div id="alerts" class="alert-grid"></div>
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
