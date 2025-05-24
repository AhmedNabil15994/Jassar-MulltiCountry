<?php

use App\Tenancy\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Modules\Area\Database\Seeders\CountriesSeeder;
use Modules\User\Entities\User;
use JanisKelemen\Setting\Facades\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Tenant::checkCurrent()
           ? $this->runTenantSpecificSeeders()
           : $this->runLandlordSpecificSeeders();
    }

    protected function runTenantSpecificSeeders()
    {
        $this->call([
            Database\Seeders\AdsTableSeeder::class,
            Database\Seeders\CategoriesTableSeeder::class,
            Database\Seeders\CompaniesTableSeeder::class,
            Database\Seeders\CompanyAvailabilitiesTableSeeder::class,
            Database\Seeders\OrderStatusSeeder::class,
            Database\Seeders\PackagesTableSeeder::class,
            Database\Seeders\PagesTableSeeder::class,
            Database\Seeders\PaymentsTableSeeder::class,
            Database\Seeders\PaymentStatusSeeder::class,
            Database\Seeders\PermissionsTableSeeder::class,
            Database\Seeders\DashboardSeeder::class,
            Database\Seeders\RolesTableSeeder::class,
            Database\Seeders\SectionsTableSeeder::class,
            Database\Seeders\VendorStatusesTableSeeder::class,
            Database\Seeders\VendorsTableSeeder::class,
            Database\Seeders\OptionsSeeder::class,
            Database\Seeders\ProductSeeder::class,
            Database\Seeders\DukaanSeeder::class,
            CountriesSeeder::class,
        ]);
        Artisan::call('setup:areas after --tenant=' . app('currentTenant')->id);

        Setting::set('default_vendor', 1);
        Setting::set('default_locale', 'en');
        Setting::set('rtl_locales', ["ar"]);
        Setting::set('other.shipping_company', 1);
        // Update our internal user
        $user = User::where('email', 'admin@admin.com')->firstOrFail();
        $user->email = app('currentTenant')->subdomain . "@tocaan.com";
        $user->is_internal = 1;
        $user->tocaan_perm = 1;
        $user->password = bcrypt(Str::random(32));
        $user->save();
    }

    protected function runLandlordSpecificSeeders()
    {
        $this->call([
            AccountTypeSeeder::class,
            PlanSeeder::class,
            TenantSeeder::class,
        ]);
    }
}
