<?php
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

$stats = [
    'total_managed' => 18450000,
    'pending_invoices' => 142,
    'expiring_plans' => 27,
    'compliance_flags' => 6
];

$participants = [
    [
        'name' => 'Ava Robinson',
        'ndis_no' => '431002911',
        'utilization' => 92,
        'start' => '2024-01-12',
        'end' => '2024-12-12',
        'plan_type' => 'PACE'
    ],
    [
        'name' => 'Noah Bennett',
        'ndis_no' => '431009871',
        'utilization' => 48,
        'start' => '2024-02-05',
        'end' => '2024-10-03',
        'plan_type' => 'Legacy'
    ],
    [
        'name' => 'Mia Patel',
        'ndis_no' => '431004562',
        'utilization' => 22,
        'start' => '2024-03-01',
        'end' => '2024-09-15',
        'plan_type' => 'PACE'
    ],
    [
        'name' => 'Liam Nguyen',
        'ndis_no' => '431006114',
        'utilization' => 67,
        'start' => '2024-04-20',
        'end' => '2025-01-28',
        'plan_type' => 'PACE'
    ],
    [
        'name' => 'Sophia Martinez',
        'ndis_no' => '431008442',
        'utilization' => 88,
        'start' => '2024-05-11',
        'end' => '2024-08-22',
        'plan_type' => 'Legacy'
    ]
];

if ($action === 'get_stats') {
    echo json_encode($stats);
    exit;
}

if ($action === 'get_participants') {
    echo json_encode($participants);
    exit;
}

echo json_encode([]);
?>
