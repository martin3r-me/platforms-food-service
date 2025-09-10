<?php

return [
    'routing' => [
        // analog zu crm: mode/prefix werden vom ModuleRouter ausgewertet
        'mode' => env('FOODSERVICE_MODE', 'subdomain'),
        'prefix' => 'food-service',
    ],

    'guard' => 'web',

    // Haupteintrag in der Modul-Navigation
    'navigation' => [
        'route' => 'food-service.dashboard',
        'icon'  => 'heroicon-o-cake',
        'order' => 40,
    ],

    // Optional: Sidebar-Metadaten
    'sidebar' => [
        'title' => 'Food Service',
        'icon'  => 'heroicon-o-cake',
    ],
];
