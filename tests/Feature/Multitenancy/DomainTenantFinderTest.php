<?php

use App\Models\Tenant;
use App\Multitenancy\DomainTenantFinder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('app.domain', 'campmaster.nl');
});

it('can find a tenant by subdomain', function () {
    // Create a tenant
    $tenant = Tenant::create([
        'name' => 'Camping De Nachtegaal',
        'domain' => 'campingdenachtegaal',
    ]);

    // Create a request with the tenant's subdomain
    $request = Request::create('https://campingdenachtegaal.campmaster.nl');

    // Use the DomainTenantFinder to find the tenant
    $finder = new DomainTenantFinder();
    $foundTenant = $finder->findForRequest($request);

    // Assert that the correct tenant was found
    expect($foundTenant->id)->toBe($tenant->id);
    expect($foundTenant->name)->toBe('Camping De Nachtegaal');
    expect($foundTenant->domain)->toBe('campingdenachtegaal');
});

it('returns null when no matching tenant is found', function () {
    // Create a request with a non-existent subdomain
    $request = Request::create('https://nonexistent.campmaster.nl');

    // Use the DomainTenantFinder to find the tenant
    $finder = new DomainTenantFinder();
    $foundTenant = $finder->findForRequest($request);

    // Assert that no tenant was found
    expect($foundTenant)->toBeNull();
});

it('returns null when the host does not contain the base domain', function () {
    // Create a request with a different domain
    $request = Request::create('https://example.com');

    // Use the DomainTenantFinder to find the tenant
    $finder = new DomainTenantFinder();
    $foundTenant = $finder->findForRequest($request);

    // Assert that no tenant was found
    expect($foundTenant)->toBeNull();
});
