<?php

namespace App\Multitenancy;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Contracts\IsTenant;

class DomainTenantFinder extends \Spatie\Multitenancy\TenantFinder\TenantFinder
{
    /**
     * Find the tenant for the given request.
     *
     * @return \App\Models\Tenant|null
     */
    public function findForRequest(Request $request): ?IsTenant
    {
        $host = $request->getHost();
        $baseDomain = Config::get('app.domain');

        // If we're not using a subdomain or we're on localhost, return null
        if ($host === $baseDomain || $host === 'localhost' || $host === '127.0.0.1') {
            return null;
        }

        // Check if the host contains the base domain
        if (! str_contains($host, $baseDomain)) {
            return null;
        }

        // Extract the subdomain from the host
        $subdomain = str_replace('.'.$baseDomain, '', $host);

        // Find the tenant by domain
        return Tenant::whereDomain($subdomain)->first();
    }
}
