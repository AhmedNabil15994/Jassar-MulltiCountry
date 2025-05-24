<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Area\Entities\City;
use Modules\Area\Entities\State;

class SeedStatesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($state , $state_id)
    {
        State::create(['title' => $state,'name'=>$state, 'status' => 1,'state_id'=>$state_id,'country_id' => 117]);
    }
}
