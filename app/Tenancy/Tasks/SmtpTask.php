<?php

namespace App\Tenancy\Tasks;

use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SmtpTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        if (! $tenant->extra_attributes->get('smtp')) {
            return;
        }

        config()->set((array) $tenant->extra_attributes->get('smtp'));
    }

    public function forgetCurrent(): void
    {
        config()->set('mail', cache('default-mail-config'));
    }

    // protected function setMailSmtp(array $smtp = null): void
    // {
    // }
}
