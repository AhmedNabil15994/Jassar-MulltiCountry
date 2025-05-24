<?php

namespace App\Jobs;

use App\Notifications\NewCustomerRegistration;
use App\Notifications\TenantInstalled;
use App\Notifications\Welcome;
use App\Tenancy\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class TenantInstallation implements ShouldQueue, NotTenantAware
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\Tenancy\Models\Tenant
     */
    protected $tenant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // @Moved to \App\Tenancy\Models\Tenant::created()
        Notification::route('mail', config('multitenancy.admin_email'))
            // ->route('discord', config('services.discord.webhook_url'))
            ->notify(new NewCustomerRegistration($this->tenant));

        $this->migrateTheDatabase();
        $this->updateAdminEmailAndPassword();
        $this->installPassport();
        $this->createWebSocketsApp();
        $this->prepareFilesystem();
        $this->updateNginxConfigs();

        if ($this->tenant->installed()) {
            Notification::route('mail', $this->tenant->email)
                ->notify(new Welcome($this->tenant));

            Notification::route('mail', config('multitenancy.admin_email'))
                // ->route('discord', config('services.discord.webhook_url'))
                ->notify(new TenantInstalled($this->tenant));
        }
    }

    protected function updateAdminEmailAndPassword()
    {
        Artisan::call('tenant:update-admin --tenant=' . $this->tenant->id);
    }

    protected function prepareFilesystem()
    {
        Artisan::call('tenant:prepare-filesystem --tenant=' . $this->tenant->id);

        $this->tenant->setSetting('install.disk', true)->save();
    }

    protected function migrateTheDatabase()
    {
        logger('migrate');
        Artisan::call('tenants:artisan "migrate --force --database=tenant" --tenant=' . $this->tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --database=tenant" --tenant=' . $this->tenant->id);

        logger('migrated');
        $this->tenant->setSetting('install.migration', true)->save();
    }

    protected function installPassport()
    {
        File::ensureDirectoryExists(storage_path('tenants/' . $this->tenant->id), 0755, true);
        Passport::loadKeysFrom(storage_path('tenants/' . $this->tenant->id));

        Artisan::call('tenants:artisan "passport:install --force" --tenant=' . $this->tenant->id);

        $this->tenant->setSetting('install.passport', true)->save();
    }

    protected function createWebSocketsApp()
    {
        $response = Http::retry(3, 100)
            ->withBasicAuth(
                config('services.websockets.username'),
                config('services.websockets.password')
            )
            ->post(config('services.websockets.base_url') . '/api/apps', [
                'id' => $this->tenant->id,
            ])
            ->throw()->json();

        $this->tenant->setSetting('websockets', [
            'id' => $response['id'],
            'key' => $response['key'],
            'secret' => $response['secret'],
        ], true)->save();

        $this->tenant->setSetting('install.websockets', true)->save();
    }

    protected function updateNginxConfigs()
    {
        Artisan::call('tenants:update-nginx-configs');
    }
}
