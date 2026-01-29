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

$invoices = [
    [
        'invoice_no' => 'INV-1001',
        'provider' => 'BrightPath Therapy',
        'participant' => 'Ava Robinson',
        'ndis_no' => '431002911',
        'service_date' => '2024-07-12',
        'status' => 'Awaiting Approval',
        'total' => 1250,
        'compliance' => 'OK',
        'budget' => 'Core',
        'support_items' => [
            ['code' => '01_011_0107_1_1', 'description' => 'Daily Living Support', 'amount' => 750],
            ['code' => '04_104_0125_6_1', 'description' => 'Assistive Tech Assessment', 'amount' => 500]
        ]
    ],
    [
        'invoice_no' => 'INV-1002',
        'provider' => 'Accessible Homes Co.',
        'participant' => 'Ava Robinson',
        'ndis_no' => '431002911',
        'service_date' => '2024-07-08',
        'status' => 'Ready for PRODA',
        'total' => 2400,
        'compliance' => 'Flagged',
        'budget' => 'Capital',
        'support_items' => [
            ['code' => '15_037_0117_1_3', 'description' => 'Home Modification Package', 'amount' => 2400]
        ]
    ],
    [
        'invoice_no' => 'INV-2001',
        'provider' => 'Northern Support Co.',
        'participant' => 'Mia Patel',
        'ndis_no' => '431004562',
        'service_date' => '2024-07-05',
        'status' => 'Paid',
        'total' => 980,
        'compliance' => 'OK',
        'budget' => 'Capacity Building',
        'support_items' => [
            ['code' => '07_101_0107_8_3', 'description' => 'Support Coordination', 'amount' => 980]
        ]
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

if ($action === 'get_invoices') {
    echo json_encode($invoices);
    exit;
}

if ($action === 'get_client') {
    $client = $participants[0];
    $client['pending_invoices'] = 12;
    $client['existing_invoices'] = [
        ['invoice_no' => 'INV-1001', 'provider' => 'BrightPath Therapy', 'email' => 'billing@brightpath.com'],
        ['invoice_no' => 'INV-1002', 'provider' => 'Accessible Homes Co.', 'email' => 'accounts@accessiblehomes.com'],
        ['invoice_no' => 'INV-1003', 'provider' => 'Northern Support Co.', 'email' => 'finance@northernco.com']
    ];
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
    $client['invoices'] = array_values(array_filter($invoices, function ($invoice) use ($client) {
        return $invoice['ndis_no'] === $client['ndis_no'];
    }));
    echo json_encode($client);
    exit;
}

echo json_encode([]);
?>
