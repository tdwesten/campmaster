<?php

return [
    'app_name' => 'Campmaster',
    'app_description' => 'A web application for managing your campsite reservations and activities.',

    'sidebar' => [
        'dashboard' => 'Dashboard',
        'reservations' => 'Reservations',
        'activities' => 'Activities',
        'guests' => 'Guests',
        'settings' => 'Settings',
        'logout' => 'Logout',
    ],

    'datatable' => [
        'columns' => 'Columns',
        'no_results' => 'No results.',
        'search_placeholder' => 'Search...',
    ],

    'pagination' => [
        'page' => 'Page',
        'of' => 'of',
        'total' => 'total',
        'previous' => 'Previous',
        'next' => 'Next',
    ],

    'guests' => [
        'breadcrumb' => 'Guests',
        'title' => 'Guests',
        'subtitle' => 'Browse and manage your guests',
        'search_placeholder' => 'Search guests...',
        'columns' => [
            'name' => 'Name',
            'email' => 'Email',
            'city' => 'City',
            'added' => 'Added',
        ],
    ],

    'bookings' => [
        'breadcrumb' => 'Bookings',
        'title' => 'Bookings',
        'subtitle' => 'Manage your bookings and reservations',
    ],
];
