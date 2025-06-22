<?php

namespace App\Multitenancy;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

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
        return $this->getTenantModel()::query()
            ->where('domain', $subdomain)
            ->first();
    }
}
