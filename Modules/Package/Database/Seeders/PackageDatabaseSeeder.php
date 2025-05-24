<?php

namespace Modules\Package\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Package\Database\Seeders\PackageTableSeeder;

class PackageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PackageTableSeeder::class);
    }
}
