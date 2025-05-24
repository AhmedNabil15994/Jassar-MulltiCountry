<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $tenant = app('currentTenant');

            $query = "
            INSERT INTO `vendors` (`id`, `slug`, `title`, `image`, `sorting`, `status`, `vendor_email`, `vendor_status_id`, `section_id`, `delivery_time_types`, `direct_delivery_message`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (1, '{\"ar\":\"متجر-{$tenant->subdomain}\",\"en\":\"{$tenant->subdomain}-vendor\"}', '{\"ar\":\"\متجر {$tenant->subdomain}\",\"en\":\"{$tenant->subdomain} Vendor\"}', 'uploads/vendors/default.png', 1, 1, '{$tenant->email}', 1, 1, '[\"direct\"]', '{\"en\":\"Delivery within a day\",\"ar\":\"التوصيل خلال يوم\"}', Null, '2020-08-14 09:13:48', '2022-04-27 00:12:26')
        ";

            $this->insert($query);

            $tagFilePath = 'sql/vendor-categories.sql';
            $this->executeSqlFile($tagFilePath);

            DB::commit();
    }

    public function insert($string)
    {
        DB::statement($string);
    }

    public function executeSqlFile($filePath)
    {
        $path = public_path($filePath);
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
