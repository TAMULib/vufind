<?php
return [
    'extends' => 'bootstrap5',
    'favicon' => 'favicon.ico',
    'css' => [
        'home-page.css'
    ],
    'icons' => [
        'aliases' => [
            'facet-unchecked' => 'FontAwesome:square-o',
            'send-sms' => 'FontAwesome:mobile',
        ],
    ],
    'helpers' => [
        'factories' => [
            'TAMU\View\Helper\Root\Record' => 'VuFind\View\Helper\Root\RecordFactory',
        ],
        'aliases' => [
            'record' => 'TAMU\View\Helper\Root\Record'
        ]
    ]
];
