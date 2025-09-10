<?php

return [
    'name' => 'Food Service',
    'description' => 'Food Service Module',
    'version' => '1.0.0',
    
    'routing' => [
        'prefix' => 'food-service',
        'middleware' => ['web', 'auth'],
    ],
    
    'guard' => 'web',
    
    'navigation' => [
        'main' => [
            'food-service' => [
                'title' => 'Food Service',
                'icon' => 'heroicon-o-cake',
                'route' => 'food-service.dashboard',
            ],
        ],
    ],
    
    'sidebar' => [
        'food-service' => [
            'title' => 'Food Service',
            'icon' => 'heroicon-o-cake',
            'items' => [
                'dashboard' => [
                    'title' => 'Dashboard',
                    'route' => 'food-service.dashboard',
                    'icon' => 'heroicon-o-home',
                ],
            ],
        ],
    ],
];
