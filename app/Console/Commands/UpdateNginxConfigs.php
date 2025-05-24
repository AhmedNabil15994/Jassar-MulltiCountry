<?php

namespace App\Console\Commands;

use App\Tenancy\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class UpdateNginxConfigs extends Command
{
    // use TenantAware;

    private $nginx_config_path = '/mnt/efs/toucart/nginx/includes/subdomains.conf';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:update-nginx-configs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (config('multitenancy.nginx_subdomains_path')) {
            $this->nginx_config_path = config('multitenancy.nginx_subdomains_path');
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $domain = config('multitenancy.main_domain');

        $contents = Tenant::with('accountType')->get()
            ->map(function ($item) use ($domain) {
                $str = "\"{$item->subdomain}.{$domain}\" {$item->accountType->slug};" . PHP_EOL;


                if ($item->domain) {
                    $str .= "\"{$item->domain}\" {$item->accountType->slug};" . PHP_EOL;

                    if (strpos($item->domain, 'www.') !== 0) {
                        $str .= "\"www.{$item->domain}\" {$item->accountType->slug};" . PHP_EOL;
                    }

                    // Set custom domain tenant name.
                    Redis::set($item->domain, $item->subdomain);
                }

                return $str;
            })
            ->join('');
        // ->implode(PHP_EOL);

        File::put($this->nginx_config_path, $contents);

        // exec('sudo service nginx configtest && sudo service nginx reload', $output);
        exec('sudo openresty -t && sudo systemctl reload openresty.service', $output);

        $this->comment("Nginx updated");
        $this->comment($contents);
        $this->comment(PHP_EOL);

        $this->comment(implode(PHP_EOL, $output));

        return 0;
    }
}
