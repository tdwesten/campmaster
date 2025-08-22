<?php

use Illuminate\Support\Facades\App;

describe('German Translations', function () {
    it('can load German translation file', function () {
        // Set locale to German
        App::setLocale('de');
        
        expect(App::getLocale())->toBe('de');
        
        // Test basic translations
        expect(__('messages.app_name'))->toBe('Campmaster');
        expect(__('messages.app_description'))->toBe('Eine Webanwendung für die Verwaltung Ihrer Campingplatz-Reservierungen und Aktivitäten.');
        
        // Test sidebar translations
        expect(__('messages.sidebar.dashboard'))->toBe('Dashboard');
        expect(__('messages.sidebar.guests'))->toBe('Gäste');
        expect(__('messages.sidebar.settings'))->toBe('Einstellungen');
        expect(__('messages.sidebar.logout'))->toBe('Abmelden');
        
        // Test datatable translations
        expect(__('messages.datatable.columns'))->toBe('Spalten');
        expect(__('messages.datatable.search_placeholder'))->toBe('Suchen...');
        expect(__('messages.datatable.no_results'))->toBe('Keine Ergebnisse.');
        
        // Test pagination translations
        expect(__('messages.pagination.page'))->toBe('Seite');
        expect(__('messages.pagination.of'))->toBe('von');
        expect(__('messages.pagination.total'))->toBe('gesamt');
        
        // Test guests section
        expect(__('messages.guests.title'))->toBe('Gäste');
        expect(__('messages.guests.subtitle'))->toBe('Durchsuchen und verwalten Sie Ihre Gäste');
        expect(__('messages.guests.columns.name'))->toBe('Name');
        expect(__('messages.guests.columns.email'))->toBe('E-Mail');
        expect(__('messages.guests.columns.city'))->toBe('Stadt');
    });
    
    it('has same structure as English translations', function () {
        $englishTranslations = trans('messages', [], 'en');
        $germanTranslations = trans('messages', [], 'de');
        
        expect(array_keys($germanTranslations))->toEqual(array_keys($englishTranslations));
        
        // Check nested structure for sidebar
        expect(array_keys($germanTranslations['sidebar']))->toEqual(array_keys($englishTranslations['sidebar']));
        
        // Check nested structure for guests
        expect(array_keys($germanTranslations['guests']))->toEqual(array_keys($englishTranslations['guests']));
        expect(array_keys($germanTranslations['guests']['columns']))->toEqual(array_keys($englishTranslations['guests']['columns']));
    });
});