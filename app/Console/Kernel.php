<?php

namespace App\Console;

use App\Console\Commands\FixProductsQuantity;
use App\Console\Commands\TranslationConvert;
use App\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Catalog\Console\CheckProductQty;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // CheckProductQty::class,
        TranslationConvert::class,
        FixProductsQuantity::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('product:checkQty')
//            ->daily(); // Run the task every day at midnight

        $schedule->command('fix:products')->cron('*/15 * * * * *');

//        $schedule->command('debugbar:clear')->daily()->at('01:00');
//        // $schedule->command('telescope:clear')->daily()->at('01:00');
//
//        // $schedule->command('tenants:artisan passport:purge')->daily()->at('03:00');
//
//        // Backup Commands
//        if (App::environment('production')) {
//            // Backup system (all files and landlord database).
//            $schedule->command('backup:run --disable-notifications')
//                ->daily()
//                ->at('01:00');
//            $schedule->command('backup:clean --disable-notifications')
//                ->daily()
//                ->at('01:15');
//
//            $schedule
//                ->command('tenants:artisan "backup:run --only-db --disable-notifications"')
//                ->daily()
//                ->at('01:45');
//            $schedule
//                ->command('tenants:artisan "backup:clean --disable-notifications"')
//                ->daily()
//                ->at('02:00');
//        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
