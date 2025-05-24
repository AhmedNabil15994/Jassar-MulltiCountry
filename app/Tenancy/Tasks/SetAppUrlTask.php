<?php

namespace App\Tenancy\Tasks;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetAppUrlTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        $this->setAppUrl($tenant->domain ?? null);
    }

    public function forgetCurrent(): void
    {
        $this->setAppUrl(null);
    }

    protected function setAppUrl(string $domain = null)
    {
        $url = $domain ? 'http://' . $domain : null;

        config()->set('app.url', $url);
        URL::forceRootUrl($url ?? '/');
        URL::forceScheme(App::environment('local') ? 'http' : 'https');
    }
}
