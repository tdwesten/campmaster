<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Multitenancy\DomainTenantFinder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainTenantFinderMiddleware
{
    /**
     * The domain tenant finder instance.
     *
     * @var \App\Multitenancy\DomainTenantFinder
     */
    protected $tenantFinder;

    /**
     * Create a new middleware instance.
     *
     * @param  \App\Multitenancy\DomainTenantFinder  $tenantFinder
     * @return void
     */
    public function __construct(DomainTenantFinder $tenantFinder)
    {
        $this->tenantFinder = $tenantFinder;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->tenantFinder->findForRequest($request);

        // Make the current tenant available through the app container
        app()->instance('currentTenant', $tenant);

        // Also bind it as a singleton for dependency injection
        app()->singleton(Tenant::class, function () use ($tenant) {
            return $tenant;
        });

        return $next($request);
    }
}
