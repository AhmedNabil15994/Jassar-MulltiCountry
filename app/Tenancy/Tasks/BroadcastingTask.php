<?php

namespace App\Tenancy\Tasks;

use Spatie\Multitenancy\Models\Tenant;
// use JanisKelemen\Setting\Facades\Setting;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class BroadcastingTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        $websockets = $tenant->setting('websockets');

        config()->set([
            // 'broadcasting.connections.pusher.key' => Setting::get('pusher.key'),
            // 'broadcasting.connections.pusher.secret' => Setting::get('pusher.secret'),
            // 'broadcasting.connections.pusher.app_id' => Setting::get('pusher.app_id'),
            'broadcasting.connections.pusher.key' => $websockets['key'] ?? null,
            'broadcasting.connections.pusher.secret' => $websockets['secret'] ?? null,
            'broadcasting.connections.pusher.app_id' => $websockets ? (string) $websockets['id'] : null,
        ]);
    }

    public function forgetCurrent(): void
    {
        config()->set([
            'broadcasting.connections.pusher.key' => config('defaults.pusher.key'),
            'broadcasting.connections.pusher.secret' => config('defaults.pusher.secret'),
            'broadcasting.connections.pusher.app_id' => config('defaults.pusher.app_id'),
        ]);
    }
}
