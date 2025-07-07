<?php

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tenant can be created with a name', function () {
    $tenant = Tenant::create([
        'name' => 'Camping De Nachtegaal',
    ]);

    expect($tenant)->toBeInstanceOf(Tenant::class)
        ->and($tenant->name)->toBe('Camping De Nachtegaal');
});

test('tenant domain is automatically generated as a slug from the name', function () {
    $tenant = Tenant::create([
        'name' => 'Camping De Nachtegaal',
    ]);

    expect($tenant->domain)->toBe('camping-de-nachtegaal');
});

test('tenant domain cannot be overridden', function () {
    $tenant = Tenant::create([
        'name' => 'Camping De Nachtegaal',
        'domain' => 'custom-domain',
    ]);

    // Domain should be generated from name, not from the provided domain
    expect($tenant->domain)->toBe('camping-de-nachtegaal');
});

test('tenant domain is unique', function () {
    // Create first tenant
    Tenant::create([
        'name' => 'Camping De Nachtegaal',
    ]);

    // Create second tenant with same name
    $tenant2 = Tenant::create([
        'name' => 'Camping De Nachtegaal',
    ]);

    // Domain should be unique
    expect($tenant2->domain)->not->toBe('camping-de-nachtegaal')
        ->and($tenant2->domain)->toContain('camping-de-nachtegaal-');
});

test('tenant uses uuid as primary key', function () {
    $tenant = Tenant::create([
        'name' => 'Camping De Nachtegaal',
    ]);

    // ID should be a UUID (36 characters with hyphens)
    expect($tenant->id)->toBeString()
        ->and(strlen($tenant->id))->toBe(36)
        ->and(preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $tenant->id))->toBe(1);
});

test('tenant factory creates a valid tenant', function () {
    $tenant = Tenant::factory()->create();

    expect($tenant)->toBeInstanceOf(Tenant::class)
        ->and($tenant->name)->toStartWith('Camping ')
        ->and($tenant->domain)->not->toBeEmpty()
        ->and($tenant->id)->toBeString()
        ->and(strlen($tenant->id))->toBe(36);
});
