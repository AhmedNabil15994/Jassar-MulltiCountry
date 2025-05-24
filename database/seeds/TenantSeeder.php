<?php

use App\Tenancy\Models\AccountType;
use App\Tenancy\Models\Plan;
use App\Tenancy\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::create([
            'name' => 'Store',
            'subdomain' => 'store',

            'phone' => '+201112222333',
            'email' => 'store@example.com',
            'password' => bcrypt('password'),

            'account_type_id' => ($accout = AccountType::whereSlug('ecommerce')->firstOrFail())->id,
            'plan_id' => Plan::where('account_type_id', $accout->id)
                ->where('price', 0)
                ->firstOrFail()->id,
        ]);

        Tenant::create([
            'name' => 'Restaurant',
            'subdomain' => 'restaurant',

            'phone' => '+9651112222333',
            'email' => 'restaurant@example.com',
            'password' => bcrypt('password'),

            'account_type_id' => ($accout = AccountType::whereSlug('restaurant')->firstOrFail())->id,
            'plan_id' => Plan::where('account_type_id', $accout->id)
                ->where('price', 0)
                ->firstOrFail()->id,
        ]);
    }
}
