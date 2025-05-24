<?php

namespace App\Tenancy\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class DoesntNeedTenant
{
    use UsesTenantModel;

    public function handle($request, Closure $next)
    {
        $tenant = $request->server('TENANT');

        if ($tenant && ! in_array($tenant, config('multitenancy.reserved_subdomains'))) {
            return $this->handleInvalidRequest();
        }

        if ($this->getTenantModel()::checkCurrent()) {
            return $this->handleInvalidRequest();
        }

        return $next($request);
    }

    public function handleInvalidRequest()
    {
        throw NoCurrentTenant::make();
    }
}
