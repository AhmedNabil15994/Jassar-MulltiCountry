<?php

namespace App\Tenancy\Tasks;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetLocalFilesystemTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        config()->set(
            'filesystems.disks.public.root',
            storage_path('app/public/t/' . $tenant->id)
        );

        config()->set(
            'filesystems.disks.public.url',
            // config('filesystems.disks.public.url') . '/t/' . $tenant->id
            url('/storage/t/' . $tenant->id)
        );
    }

    public function forgetCurrent(): void
    {
        config()->set(
            'filesystems.disks.public.root',
            storage_path('app/public')
        );

        config()->set(
            'filesystems.disks.public.url',
            url('/storage')
        );
    }
}
