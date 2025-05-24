<?php

namespace App\Tenancy\Tasks;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetS3FilesystemTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        config()->set(
            'filesystems.disks.s3.root',
            '/t/' . $tenant->id,
        );

        // config()->set(
        //     'filesystems.disks.s3.url',
        //     config('filesystems.disks.s3.url') . 't/' . $tenant->id,
        // );
    }

    public function forgetCurrent(): void
    {
        config()->set(
            'filesystems.disks.s3.root',
            '',
        );

        // config()->set(
        //     'filesystems.disks.public.url',
        //     strpos(config('filesystems.disks.s3.url'), '/t/')
        //         ? explode('/t/', config('filesystems.disks.s3.url'))[0]
        //         : config('filesystems.disks.s3.url'),
        // );
    }
}
