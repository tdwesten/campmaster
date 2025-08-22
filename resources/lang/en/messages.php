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
        'actions' => [
            'create' => 'Create guest',
        ],
        'edit' => [
            'title' => 'Edit Guest ":name"',
            'subtitle' => 'Edit guest details',
            'form_title' => 'General Information',
            'success_message' => [
                'title' => 'Guest updated successfully',
                'description' => 'The guest has been successfully updated.',
            ],
            'errors' => 'Failed to update guest.',
            'buttons' => [
                'update' => 'Update',
                'save' => 'Save',
                'cancel' => 'Cancel',
            ],
            'fields' => [
                'firstname' => 'First Name',
                'lastname' => 'Last Name',
                'email' => 'Email',
                'street' => 'Street',
                'house_number' => 'House Number',
                'postal_code' => 'Postal Code',
                'city' => 'City',
                'country' => 'Country',
            ],
        ],
        'create' => [
            'title' => 'Create Guest',
            'subtitle' => 'Create a new guest',
            'success_message' => [
                'title' => 'Guest created successfully',
                'description' => 'The guest has been successfully created.',
            ],
            'errors' => 'Failed to create guest.',
            'buttons' => [
                'create' => 'Create',
                'cancel' => 'Cancel',
            ],
            'fields' => [
                'firstname' => 'First Name',
                'lastname' => 'Last Name',
                'email' => 'Email',
                'street' => 'Street',
                'house_number' => 'House Number',
                'postal_code' => 'Postal Code',
                'city' => 'City',
                'country' => 'Country',
            ],
        ],
    ],

    'bookings' => [
        'breadcrumb' => 'Bookings',
        'title' => 'Bookings',
        'subtitle' => 'Manage your bookings and reservations',
    ],
];
