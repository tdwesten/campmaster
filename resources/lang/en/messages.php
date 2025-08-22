<?php

return [
    'app_name' => 'Campmaster',
    'app_description' => 'A web application for managing your campsite reservations and activities.',

    'sidebar' => [
        'main' => 'General',
        'configuration' => 'Configuration',
        'dashboard' => 'Dashboard',
        'reservations' => 'Reservations',
        'activities' => 'Activities',
        'guests' => 'Guests',
        'settings' => 'Settings',
        'logout' => 'Logout',
        'sites' => 'Sites',
        'site_categories' => 'Site Categories',
    ],

    'datatable' => [
        'columns' => 'Columns',
        'no_results' => 'No results.',
        'search_placeholder' => 'Search...',
    ],

    'common' => [
        'buttons' => [
            'edit' => 'Edit',
            'update' => 'Update',
            'create' => 'Create',
            'cancel' => 'Cancel',
        ],
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

    'sites' => [
        'breadcrumb' => 'Sites',
        'title' => 'Sites',
        'subtitle' => 'Manage your campsite sites and other rentable properties',
        'actions' => [
            'create' => 'Create site',
        ],
        'columns' => [
            'name' => 'Name',
            'category' => 'Category',
            'added' => 'Added',
            'description' => 'Description',
        ],
        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
            'category' => 'Category',
        ],
        'placeholders' => [
            'select_category' => 'Select a category',
        ],
        'create' => [
            'title' => 'Create Site',
            'subtitle' => 'Create a new site',
            'success_message' => [
                'title' => 'Site created successfully',
                'description' => 'The site has been successfully created.',
            ],
            'buttons' => [
                'create' => 'Create',
                'cancel' => 'Cancel',
            ],
        ],
        'edit' => [
            'title' => 'Edit Site ":name"',
            'subtitle' => 'Edit site details',
            'form_title' => 'General Information',
            'success_message' => [
                'title' => 'Site updated successfully',
                'description' => 'The site has been successfully updated.',
            ],
            'buttons' => [
                'update' => 'Update',
                'cancel' => 'Cancel',
            ],
        ],
    ],

    'site_categories' => [
        'breadcrumb' => 'Site Categories',
        'title' => 'Site Categories',
        'subtitle' => 'Manage your site categories',
        'actions' => [
            'create' => 'Create category',
        ],
        'columns' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'sites' => 'Sites',
            'added' => 'Added',
            'description' => 'Description',
        ],
        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
        ],
        'create' => [
            'title' => 'Create Category',
            'subtitle' => 'Create a new site category',
            'success_message' => [
                'title' => 'Category created successfully',
                'description' => 'The category has been successfully created.',
            ],
            'buttons' => [
                'create' => 'Create',
                'cancel' => 'Cancel',
            ],
        ],
        'edit' => [
            'title' => 'Edit Category ":name"',
            'subtitle' => 'Edit category details',
            'form_title' => 'General Information',
            'success_message' => [
                'title' => 'Category updated successfully',
                'description' => 'The category has been successfully updated.',
            ],
            'buttons' => [
                'update' => 'Update',
                'cancel' => 'Cancel',
            ],
        ],
    ],

    'settings' => [
        'title' => 'Settings',
        'description' => 'Manage your profile and account settings',
        'appearance' => [
            'title' => 'Appearance settings',
            'description' => "Update your account's appearance settings",
            'language' => [
                'title' => 'Language',
                'description' => 'Choose the language used across the application',
                'select_label' => 'Select language',
                'en' => 'English',
                'nl' => 'Dutch',
            ],
        ],
    ],
];
