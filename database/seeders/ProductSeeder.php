<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagFilePath = 'sql/products.sql';
        $this->executeSqlFile($tagFilePath);
    }

    public function executeSqlFile($filePath)
    {
        $path = public_path($filePath);
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
