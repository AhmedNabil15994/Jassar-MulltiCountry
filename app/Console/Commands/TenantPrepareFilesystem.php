<?php

namespace App\Console\Commands;

use App\Tenancy\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class TenantPrepareFilesystem extends Command
{
    // use TenantAware;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:prepare-filesystem {--tenant=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->line('The tenant is ' . app('currentTenant')->name);
        $tenant_id = $this->option('tenant');

        if (! $tenant_id) {
            $this->error('Tenant ID is required.');
            return 1;
        }

        $currentTenant = Tenant::with('accountType')->findOrFail($tenant_id);

        $shared_directory = $currentTenant->accountType->slug === 'restaurant'
            ? 'shared-restaurant'
            : 'shared';

        $shared_tenant_files = Storage::disk('s3')->allFiles($shared_directory);

        foreach ($shared_tenant_files as $file) {
            Storage::disk('s3')->copy(
                $file,
                str_replace($shared_directory . '/', "t/{$currentTenant->id}/", $file),
            );
        }

        return 0;
    }
}
