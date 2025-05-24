<?php

namespace App\Tenancy\Tasks;

use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchDatabaseConnectionTask implements SwitchTenantTask
{
    use UsesMultitenancyConfig;

    public function makeCurrent(Tenant $tenant): void
    {
        $connectionName = $this->tenantDatabaseConnectionName();

        config()->set([
            "database.default" => $connectionName,
        ]);

        DB::purge($connectionName);
    }

    public function forgetCurrent(): void
    {
        $connectionName = $this->landlordDatabaseConnectionName();

        config([
            "database.default" => $connectionName,
        ]);

        DB::purge($connectionName);
    }
}
