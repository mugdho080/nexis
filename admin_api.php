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

if ($action === 'get_client') {
    $client = $participants[0];
    $client['pending_invoices'] = 12;
    $client['budget'] = [
        'total' => 825000,
        'spent' => 412500,
        'quarantined' => 185000
    ];
    $client['categories'] = [
        [
            'key' => 'core',
            'name' => 'Core',
            'allocated' => 420000,
            'spent' => 210000,
            'quarantined' => 90000,
            'items' => [
                ['label' => 'Daily Living Support', 'amount' => 140000],
                ['label' => 'Community Participation', 'amount' => 70000]
            ]
        ],
        [
            'key' => 'capital',
            'name' => 'Capital',
            'allocated' => 240000,
            'spent' => 112500,
            'quarantined' => 45000,
            'items' => [
                ['label' => 'Assistive Technology', 'amount' => 95000],
                ['label' => 'Home Modifications', 'amount' => 65000]
            ]
        ],
        [
            'key' => 'capacity',
            'name' => 'Capacity Building',
            'allocated' => 165000,
            'spent' => 90000,
            'quarantined' => 50000,
            'items' => [
                ['label' => 'Support Coordination', 'amount' => 55000],
                ['label' => 'Improved Daily Living', 'amount' => 35000]
            ]
        ]
    ];
    $client['alerts'] = [
        ['type' => 'Overspend', 'message' => 'Projected to exhaust Core funds in 27 days.'],
        ['type' => 'Underspend', 'message' => 'Capacity Building budget is 42% unspent with 90 days left.']
    ];
    $client['quarantines'] = [
        ['provider' => 'BrightPath Therapy', 'service' => 'Daily Living Support', 'amount' => 50000],
        ['provider' => 'Accessible Homes Co.', 'service' => 'Home Modification Package', 'amount' => 30000]
    ];
    $client['providers'] = [
        ['name' => 'BrightPath Therapy', 'service' => 'Daily Living Support', 'status' => 'Active'],
        ['name' => 'Accessible Homes Co.', 'service' => 'Home Modification Package', 'status' => 'Active'],
        ['name' => 'Northern Support Co.', 'service' => 'Support Coordination', 'status' => 'Pending']
    ];
    echo json_encode($client);
    exit;
}

echo json_encode([]);
?>
