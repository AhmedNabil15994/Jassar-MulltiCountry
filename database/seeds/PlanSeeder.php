<?php

use App\Tenancy\Models\AccountType;
use App\Tenancy\Models\Plan;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = Plan::count();

            if ($count == 0) {
                $counter = 0;
                foreach (AccountType::all() as $account_type) {
                    $counter++;

                    Plan::create([
                        'account_type_id' => $account_type->id,

                        'name' => 'باقة تجريبية',
                        'price' => 0 * $counter,
                    ])
                    ->setSetting('max_products_num', 100)
                    ->setSetting('max_branches_num', 1)
                    ->save();

                    Plan::create([
                        'account_type_id' => $account_type->id,

                        'name' => 'باقة اسبوعية',
                        'price' => 50 * $counter,
                    ])
                    ->setSetting('max_products_num', 100)
                    ->setSetting('max_branches_num', 1)
                    ->save();

                    Plan::create([
                        'account_type_id' => $account_type->id,

                        'name' => 'باقة شهرية',
                        'price' => 70 * $counter,
                    ])
                    ->setSetting('max_products_num', 500)
                    ->setSetting('max_branches_num', 5)
                    ->save();

                    Plan::create([
                        'account_type_id' => $account_type->id,

                        'name' => 'باقة سنوية',
                        'price' => 100 * $counter,
                    ])
                    ->setSetting('max_products_num', 1000)
                    ->setSetting('max_branches_num', 10)
                    ->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
