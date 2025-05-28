<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;
use App\Models\Tenant;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\StorageDrivers\Cache\CacheStorage;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByDomainException;

class TenantMiddleware extends InitializeTenancyByDomain
{
    public function __construct(Tenancy $tenancy, DomainTenantResolver $resolver)
    {
        parent::__construct($tenancy, $resolver);
    }

    public function handle($request, Closure $next)
    {
        $domain = $request->getHost();
        
        if ($domain === 'localhost' || $domain === '127.0.0.1') {
            // For local development, use the first tenant
            $tenant = Tenant::first();
            if (!$tenant) {
                throw new TenantCouldNotBeIdentifiedByDomainException($domain . ' (no default tenant found for localhost)');
            }
            $this->tenancy->initialize($tenant);
            return $next($request);
        } else {
            // For actual domain-based tenancy, let the parent handle it or resolver will throw exception
            // The parent's handle method will call the resolver we've set up.
            // If the resolver can't find a tenant, it will throw an exception.
            try {
                return parent::handle($request, $next);
            } catch (TenantCouldNotBeIdentifiedByDomainException $e) {
                // Re-throw or handle as needed, perhaps return a specific JSON response
                throw new TenantCouldNotBeIdentifiedByDomainException($domain);
            }
        }
    }
} 