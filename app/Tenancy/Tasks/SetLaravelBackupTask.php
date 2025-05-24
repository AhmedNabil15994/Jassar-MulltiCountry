<?php

namespace App\Tenancy\Tasks;

use Illuminate\Support\Facades\App;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetLaravelBackupTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        if (! App::runningInConsole()) {
            return;
        }

        config()->set(
            'backup.backup.name',
            "tenant-{$tenant->id}",
        );

        config()->set(
            'backup.backup.source.databases',
            [
                config('multitenancy.tenant_database_connection_name'),
            ],
        );
    }

    public function forgetCurrent(): void
    {
        if (! App::runningInConsole()) {
            return;
        }

        config()->set(
            'backup.backup.name',
            'system',
            // config('app.name', 'laravel-backup'),
        );

        config()->set(
            'backup.backup.source.databases',
            [
                config('multitenancy.landlord_database_connection_name'),
            ],
        );
    }
}
