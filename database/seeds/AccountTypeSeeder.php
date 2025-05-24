<?php

use App\Tenancy\Models\AccountType;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountTypeSeeder extends Seeder
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
            $count = AccountType::count();

            if ($count == 0) {
                AccountType::create([
                    'name' => 'متجر إلكتروني',
                    'slug' => 'ecommerce',
                ]);
                AccountType::create([
                    'name' => 'مطعم',
                    'slug' => 'restaurant',
                ]);
                // AccountType::create([
                //     'name' => 'فرد',
                // ]);
                // AccountType::create([
                //     'name' => 'مؤسسة تعليمية',
                // ]);
                // AccountType::create([
                //     'name' => 'شركة',
                // ]);
                // AccountType::create([
                //     'name' => 'مؤسسة خيرية',
                // ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
