<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;
use Nnjeim\World\Actions\SeedCountry;
use Nnjeim\World\Actions\SeedCustomTableSeeder;

class CountriesSeeder extends Seeder
{

	public function run()
	{
		$this->call([
//			SeedCountry::class,
//			SeedCustomTableSeeder::class,
            (new SeedCitiesTableSeeder())->run(),
		]);
	}
}
