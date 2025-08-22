<?php

return [
    'app_name' => 'Campmaster',
    'app_description' => 'Een webapplicatie voor het beheren van je campingreserveringen en activiteiten.',

    'sidebar' => [
        'main' => 'Algemeen',
        'configuration' => 'Configuratie',
        'dashboard' => 'Dashboard',
        'reservations' => 'Reserveringen',
        'activities' => 'Activiteiten',
        'guests' => 'Gasten',
        'settings' => 'Instellingen',
        'logout' => 'Uitloggen',
        'sites' => 'Plaatsen',
        'site_categories' => 'Plaatstypen',
    ],

    'datatable' => [
        'columns' => 'Kolommen',
        'no_results' => 'Geen resultaten.',
        'search_placeholder' => 'Zoeken...',
    ],

    'common' => [
        'buttons' => [
            'edit' => 'Bewerken',
            'update' => 'Bijwerken',
            'create' => 'Aanmaken',
            'cancel' => 'Annuleren',
        ],
    ],

    'pagination' => [
        'page' => 'Pagina',
        'of' => 'van',
        'total' => 'totaal',
        'previous' => 'Vorige',
        'next' => 'Volgende',
    ],

    'guests' => [
        'breadcrumb' => 'Gasten',
        'title' => 'Gasten',
        'subtitle' => 'Blader en beheer je gasten',
        'search_placeholder' => 'Zoek gasten...',
        'columns' => [
            'name' => 'Naam',
            'email' => 'E-mailadres',
            'city' => 'Plaats',
            'added' => 'Toegevoegd',
        ],
        'actions' => [
            'create' => 'Gast aanmaken',
        ],
        'edit' => [
            'title' => 'Gast ":name" bewerken',
            'subtitle' => 'Gastgegevens bewerken',
            'form_title' => 'Algemene informatie',
            'success_message' => [
                'title' => 'Gast succesvol bijgewerkt',
                'description' => 'De gast is succesvol bijgewerkt.',
            ],
            'errors' => 'Bijwerken van gast mislukt.',
            'buttons' => [
                'update' => 'Bijwerken',
                'save' => 'Opslaan',
                'cancel' => 'Annuleren',
            ],
            'fields' => [
                'firstname' => 'Voornaam',
                'lastname' => 'Achternaam',
                'email' => 'E-mailadres',
                'street' => 'Straat',
                'house_number' => 'Huisnummer',
                'postal_code' => 'Postcode',
                'city' => 'Plaats',
                'country' => 'Land',
            ],
        ],
        'create' => [
            'title' => 'Gast aanmaken',
            'subtitle' => 'Maak een nieuwe gast aan',
            'success_message' => [
                'title' => 'Gast succesvol aangemaakt',
                'description' => 'De gast is succesvol aangemaakt.',
            ],
            'errors' => 'Aanmaken van gast mislukt.',
            'buttons' => [
                'create' => 'Aanmaken',
                'cancel' => 'Annuleren',
            ],
            'fields' => [
                'firstname' => 'Voornaam',
                'lastname' => 'Achternaam',
                'email' => 'E-mailadres',
                'street' => 'Straat',
                'house_number' => 'Huisnummer',
                'postal_code' => 'Postcode',
                'city' => 'Plaats',
                'country' => 'Land',
            ],
        ],
    ],

    'bookings' => [
        'breadcrumb' => 'Boekingen',
        'title' => 'Boekingen',
        'subtitle' => 'Beheer je boekingen en reserveringen',
    ],

    'sites' => [
        'breadcrumb' => 'Plaatsen',
        'title' => 'Plaatsen',
        'subtitle' => 'Beheer je kampeerplaatsen en andere verhuureenheden',
        'actions' => [
            'create' => 'Plaats aanmaken',
        ],
        'columns' => [
            'name' => 'Naam',
            'category' => 'Categorie',
            'added' => 'Toegevoegd',
            'description' => 'Beschrijving',
        ],
        'fields' => [
            'name' => 'Naam',
            'description' => 'Beschrijving',
            'category' => 'Categorie',
        ],
        'placeholders' => [
            'select_category' => 'Selecteer een categorie',
        ],
        'create' => [
            'title' => 'Plaats aanmaken',
            'subtitle' => 'Maak een nieuwe plaats aan',
            'success_message' => [
                'title' => 'Plaats succesvol aangemaakt',
                'description' => 'De plaats is succesvol aangemaakt.',
            ],
            'buttons' => [
                'create' => 'Aanmaken',
                'cancel' => 'Annuleren',
            ],
        ],
        'edit' => [
            'title' => 'Plaats ":name" bewerken',
            'subtitle' => 'Plaatsgegevens bewerken',
            'form_title' => 'Algemene informatie',
            'success_message' => [
                'title' => 'Plaats succesvol bijgewerkt',
                'description' => 'De plaats is succesvol bijgewerkt.',
            ],
            'buttons' => [
                'update' => 'Bijwerken',
                'cancel' => 'Annuleren',
            ],
        ],
    ],

    'site_categories' => [
        'breadcrumb' => 'Plaatstypen',
        'title' => 'Plaatstypen',
        'subtitle' => 'Beheer je plaatscategorieën',
        'actions' => [
            'create' => 'Categorie aanmaken',
        ],
        'columns' => [
            'name' => 'Naam',
            'slug' => 'Slug',
            'sites' => 'Plaatsen',
            'added' => 'Toegevoegd',
            'description' => 'Beschrijving',
        ],
        'fields' => [
            'name' => 'Naam',
            'description' => 'Beschrijving',
        ],
        'create' => [
            'title' => 'Categorie aanmaken',
            'subtitle' => 'Maak een nieuwe plaatscategorie aan',
            'success_message' => [
                'title' => 'Categorie succesvol aangemaakt',
                'description' => 'De categorie is succesvol aangemaakt.',
            ],
            'buttons' => [
                'create' => 'Aanmaken',
                'cancel' => 'Annuleren',
            ],
        ],
        'edit' => [
            'title' => 'Categorie ":name" bewerken',
            'subtitle' => 'Categoriegegevens bewerken',
            'form_title' => 'Algemene informatie',
            'success_message' => [
                'title' => 'Categorie succesvol bijgewerkt',
                'description' => 'De categorie is succesvol bijgewerkt.',
            ],
            'buttons' => [
                'update' => 'Bijwerken',
                'cancel' => 'Annuleren',
            ],
        ],
    ],

    'settings' => [
        'title' => 'Instellingen',
        'description' => 'Beheer je profiel- en accountinstellingen',
        'appearance' => [
            'title' => 'Weergave-instellingen',
            'description' => 'Werk de weergave-instellingen van je account bij',
            'language' => [
                'title' => 'Taal',
                'description' => 'Kies de taal die in de applicatie wordt gebruikt',
                'select_label' => 'Selecteer taal',
                'en' => 'Engels',
                'nl' => 'Nederlands',
            ],
        ],
    ],
];
