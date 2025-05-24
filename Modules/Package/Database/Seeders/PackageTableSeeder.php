<?php

namespace Modules\Package\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Package\Entities\Package;
use Illuminate\Database\Eloquent\Model;

class PackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->insertData();

    }

    public function insertData(){
        $data = [ [
            "title" => [
                "ar" => "باقة مجانيه",
                "en" => "Free Package"
            ],
            "duration"=> 3 ,
            "is_free" => true

        ] ,
        [
            "title" => [
                "ar" => "باقة ",
                "en" => " Package"
            ],
            "duration"=> 20 ,
            "price"   => 5,
            "is_free" => false
        ]
    ];
        foreach ($data as $object) {
            Package::create($object);
        }
    }
}
