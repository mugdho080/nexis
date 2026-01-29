<?php
header('Content-Type: application/json');

require __DIR__ . '/db.php';

$endpoint = $_GET['endpoint'] ?? 'summary';

$mockData = [
    'summary' => [
        'participants' => 1280,
        'providers' => 540,
        'invoices_pending' => 86,
        'invoices_paid' => 1240,
        'total_budget' => 8250000,
        'total_spent' => 4375000,
        'total_committed' => 2120000
    ],
    'alerts' => [
        [
            'type' => 'Overspend Risk',
            'message' => 'Participant NDIS-48291 projected to run out of Core funds in 27 days.',
            'status' => 'warning'
        ],
        [
            'type' => 'Underspend Opportunity',
            'message' => 'Participant NDIS-91374 has 42% Capacity Building funds remaining with 90 days left.',
            'status' => 'info'
        ]
    ],
    'roles' => [
        [
            'role' => 'Participants',
            'capabilities' => [
                'Budget oversight & approval workflows',
                'Reimbursements & claims tracking',
                'Mobile notifications for spending alerts'
            ]
        ],
        [
            'role' => 'Support Coordinators',
            'capabilities' => [
                'Multi-client oversight',
                'Utilization reports & plan forecasting',
                'Capacity building milestones'
            ]
        ],
        [
            'role' => 'Service Providers',
            'capabilities' => [
                'Fast-track invoice creation',
                'Payment status tracking',
                'Service agreement management'
            ]
        ]
    ]
];

if ($mysqli) {
    if ($endpoint === 'summary') {
        $query = "SELECT 
            (SELECT COUNT(*) FROM participants) AS participants,
            (SELECT COUNT(*) FROM providers) AS providers,
            (SELECT COUNT(*) FROM invoices WHERE status = 'Pending') AS invoices_pending,
            (SELECT COUNT(*) FROM invoices WHERE status = 'Paid') AS invoices_paid,
            (SELECT SUM(total_allocated) FROM budgets) AS total_budget,
            (SELECT SUM(spent) FROM budgets) AS total_spent,
            (SELECT SUM(committed) FROM budgets) AS total_committed";
        $result = $mysqli->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            echo json_encode($row);
            exit;
        }
    }
    if ($endpoint === 'alerts') {
        $query = "SELECT type, message, status FROM alerts ORDER BY created_at DESC LIMIT 5";
        $result = $mysqli->query($query);
        if ($result) {
            $alerts = [];
            while ($row = $result->fetch_assoc()) {
                $alerts[] = $row;
            }
            echo json_encode($alerts);
            exit;
        }
    }
}

echo json_encode($mockData[$endpoint] ?? []);
?>
