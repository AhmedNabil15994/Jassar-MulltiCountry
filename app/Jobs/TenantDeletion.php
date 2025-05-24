<?php

namespace App\Jobs;

use App\Notifications\TenantDeleted;
use App\Tenancy\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class TenantDeletion implements ShouldQueue, NotTenantAware
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
    public function __construct(array $tenant)
    {
        // $this->tenant = Tenant::make($tenant);
        $this->tenant = (object) $tenant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Delete custom domain tenant name from redis.
        if ($this->tenant->domain) {
            Redis::del($this->tenant->domain);
        }

        Artisan::call('tenants:update-nginx-configs');

        // Delete tenant database
        $connection = config('multitenancy.tenant_database_connection_name');
        DB::connection($connection)
            ->statement("DROP DATABASE IF EXISTS `{$this->tenant->database}`");

        // Delete tenant directory on the local disk
        File::deleteDirectory(storage_path('app/public/t/' . $this->tenant->id));

        // Delete passport directory on the local disk
        File::deleteDirectory(storage_path("tenants/{$this->tenant->id}"));

        // Delete tenant directory on Amazon S3
        Storage::deleteDirectory("t/{$this->tenant->id}");

        // Delete tenant backup directory on Amazon S3
        Storage::disk('backup')->deleteDirectory("tenant-{$this->tenant->id}");

        // notify our admin
        Notification::route('mail', config('multitenancy.admin_email'))
            // ->route('discord', config('services.discord.webhook_url'))
            ->notify(new TenantDeleted($this->tenant));
    }
}
