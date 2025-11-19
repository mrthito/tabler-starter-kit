<?php

return [
    'services' => [
        [
            'name' => 'API Production',
            'url' => '/api/health',
            'status' => 'degraded',
        ],
        [
            'name' => 'Frontend Application',
            'url' => '/up',
            'status' => 'operational',
        ],
        [
            'name' => 'Database Cluster',
            'url' => 'database::mysql',
            'status' => 'operational',
        ],
        [
            'name' => 'Login Page',
            'url' => '/login',
            'status' => 'down',
        ],
    ],
];
