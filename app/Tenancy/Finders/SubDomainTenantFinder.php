<?php

namespace App\Tenancy\Finders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class SubDomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {
        
        $tenant = $this->getTenantModel()->first();

        return $tenant;
    }
}
