<?php

namespace App\Console\Commands;

use App\Tenancy\Models\Tenant;
use Illuminate\Console\Command;
use Modules\User\Entities\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class TenantUpdateAdmin extends Command
{
    use TenantAware;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:update-admin {--tenant=}';

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

        $currentTenant = app('currentTenant');

        User::where('email', 'admin@tocaan.com')
            ->limit(1)
            ->update([
                'is_internal' => 1,
                'password' => $currentTenant->password,
            ]);

        return 0;
    }
}
