<?php

namespace App\Multitenancy;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        $host = $request->getHost();
        $baseDomain = config('app.domain');

        // If the host doesn't contain the base domain, return null
        if (!str_contains($host, $baseDomain)) {
            return null;
        }

        // Extract the subdomain part
        $subdomain = str_replace('.' . $baseDomain, '', $host);

        // Find the tenant by domain
        return app(IsTenant::class)::query()
            ->where('domain', $subdomain)
            ->first();
    }
}
