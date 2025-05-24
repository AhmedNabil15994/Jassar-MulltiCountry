<?php

namespace App\Jobs;

use App\Jobs\TenantDeletion;
use App\Jobs\TenantInstallation;
use App\Notifications\NewCustomerRegistration;
use App\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Spatie\Multitenancy\Jobs\NotTenantAware;
use Spatie\WebhookClient\ProcessWebhookJob as SpatieProcessWebhookJob;

class ProcessWebhookJob extends SpatieProcessWebhookJob implements NotTenantAware
{
    public function handle()
    {
        // $this->webhookCall // contains an instance of `WebhookCall`



        switch ($this->webhookCall->payload['event']) {
            case 'TENANT_CREATED':
                $this->completeTenantInstallation();
                break;
            case 'TENANT_DOMAIN_UPDATED':
                $this->updateNginxConfigs();
                break;
            case 'TENANT_DELETED':
                $this->completeTenantDeletion();
                break;
            // case 'TENANT_UPDATED':
            //     # code...
            //     break;
            default:
                logger("Unrecognized event name {$this->webhookCall->payload['event']}.");
                break;
        }
    }

    protected function completeTenantInstallation()
    {
        $tenant = Tenant::with('accountType')
            ->findOrFail($this->webhookCall->payload['tenant_id']);

        // notify our admin
        Notification::route('mail', config('multitenancy.admin_email'))
            // ->route('discord', config('services.discord.webhook_url'))
            ->notify(new NewCustomerRegistration($tenant));

        dispatch(new TenantInstallation($tenant))
            ->onQueue($this->webhookCall->payload['queue']);
        // ->onQueue($tenant->accountType->slug);
    }

    protected function completeTenantDeletion()
    {
        dispatch(new TenantDeletion($this->webhookCall->payload['data']))
            ->onQueue($this->webhookCall->payload['queue']);
        // ->onQueue($model->accountType->slug);
    }

    protected function updateNginxConfigs()
    {
        Artisan::call('tenants:update-nginx-configs');
    }
}
