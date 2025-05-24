<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Modules\DeveloperTools\Entities\SiteColor;
use Illuminate\Support\Facades\Cache;

class DukaanSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $this->siteColors();
        $this->siteHeader();
        $this->HomeSections();
    }

    public function insert($string)
    {
        DB::statement($string);
    }

    private function HomeSections()
    {
        $home = "INSERT INTO `app_homes` (`id`, `title`, `short_title`, `description`, `status`, `order`, `type`, `display_type`, `grid_columns_count`, `start_at`, `end_at`, `deleted_at`, `created_at`, `updated_at`)
         VALUES (NULL, '{\"ar\": null}', NULL, '{\"ar\": null}', '1', '1', 'sliders', 'carousel', '1', NULL, NULL, NULL, '2022-12-15 19:58:11', '2022-12-15 19:58:11'), 
         (NULL, '{\"ar\": null, \"en\": \"Browse categories\"}', NULL, '{\"ar\": null}', '1', '2', 'categories', 'carousel', NULL, NULL, NULL, NULL, '2022-12-15 20:11:03', '2022-12-15 20:11:03'), 
         (NULL, '{\"ar\": null, \"en\": \"Browse categories-2\"}', NULL, '{\"ar\": null}', '1', '2', 'categories', 'grid', '6', NULL, NULL, NULL, '2022-12-15 20:14:44', '2022-12-15 20:14:44'), 
         (NULL, '{\"ar\": null, \"en\": \"New Arrive\"}', NULL, '{\"ar\": null}', '1', '4', 'products', 'carousel', NULL, NULL, NULL, NULL, '2022-12-15 20:15:50', '2022-12-15 20:15:50')
        ";
        $this->insert($home);

        $homables = 'INSERT INTO `homables` (`id`, `app_home_id`, `homable_type`, `homable_id`, `status`, `created_at`, `updated_at`) VALUES (NULL, \'1\', \'Modules\\\Advertising\\\Entities\\\AdvertisingGroup\', \'1\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'1\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'2\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'3\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'4\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'5\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'6\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'7\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'8\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'9\', \'1\', NULL, NULL), (NULL, \'3\', \'Modules\\\Catalog\\\Entities\\\Category\', \'10\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'1\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'2\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'3\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'4\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'5\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'6\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'7\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'8\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'9\', \'1\', NULL, NULL), (NULL, \'2\', \'Modules\\\Catalog\\\Entities\\\Category\', \'10\', \'1\', NULL, NULL)
        ';
        $this->insert($homables);
    }

    private function siteHeader()
    {
        $tenantSubdomain = app('currentTenant')->subdomain;
        $menu = "INSERT INTO `admin_menus` (`id`, `name`, `created_at`, `updated_at`) VALUES (NULL, 'header menu', '2022-12-15 19:23:02', '2022-12-15 19:23:02')";
        $this->insert($menu);

        $menuItems = 'INSERT INTO `admin_menu_items` (`id`, `label`, `link`, `parent`, `sort`, `class`, `type`, `itemable_type`, `itemable_id`, `menu`, `depth`, `json_data`, `created_at`, `updated_at`) 
                        VALUES (NULL, \'{\"en\":\"Home\",\"ar\":\"الرئيسية\"}\', \'{\"en\":\"\\/\",\"ar\":\"\\/\"}\', \'0\', \'0\', NULL, \'custom_link\', NULL, NULL, \'1\', \'0\', NULL, \'2022-12-15 19:23:16\', \'2022-12-15 19:34:28\'),
                        (NULL, \'{\"en\":\"About us\",\"ar\":\"معلومات عنا\"}\', NULL, \'5\', \'2\', NULL, \'page\', \'Modules\\\Page\\\Entities\\\Page\', \'1\', \'1\', \'1\', NULL, \'2022-12-15 19:24:45\', \'2022-12-15 19:34:28\'),
                          (NULL, \'{\"en\":\"Terms & Conditions\",\"ar\":\"الشروط والأحكام\"}\', NULL, \'5\', \'4\', NULL, \'page\', \'Modules\\\Page\\\Entities\\\Page\', \'2\', \'1\', \'1\', NULL, \'2022-12-15 19:26:13\', \'2022-12-15 19:34:38\'),
                          (NULL, \'{\"en\":\"Privacy and Policy\",\"ar\":\"سياسة الخصوصية\"}\', NULL, \'5\', \'3\', NULL, \'page\', \'Modules\\\Page\\\Entities\\\Page\', \'3\', \'1\', \'1\', NULL, \'2022-12-15 19:26:53\', \'2022-12-15 19:34:28\'),
                          (NULL, \'{\"en\":\"Pages\",\"ar\":\"الصفحات\"}\', \'{\"en\":\"#\",\"ar\":\"#\"}\', \'0\', \'1\', NULL, \'custom_link\', NULL, NULL, \'1\', \'0\', NULL, \'2022-12-15 19:27:14\', \'2022-12-15 19:34:28\'),
                          (NULL, \'{\"en\":\"Gifts\"}\', NULL, \'0\', \'7\', NULL, \'category\', \'Modules\\\Catalog\\\Entities\\\Category\', \'3\', \'1\', \'0\', NULL, \'2022-12-15 19:31:32\', \'2022-12-15 19:34:38\'),
                          (NULL, \'{\"en\":\"graduating\"}\', NULL, \'0\', \'6\', NULL, \'category\', \'Modules\\\Catalog\\\Entities\\\Category\', \'6\', \'1\', \'0\', NULL, \'2022-12-15 19:32:00\', \'2022-12-15 19:34:38\'),
                          (NULL, \'{\"en\":\"Categories\",\"ar\":\"الأقسام\"}\', NULL, \'0\', \'5\', NULL, \'category_list\', NULL, NULL, \'1\', \'0\', \'{\"menu_type\":\"nsted_menu\",\"categories\":[\"10\",\"9\",\"8\",\"7\"]}\', \'2022-12-15 19:33:10\', \'2022-12-15 19:34:38\'),
                          (NULL, \'{\"en\":\"More\",\"ar\":\"More\"}\', NULL, \'0\', \'8\', NULL, \'category_list\', NULL, NULL, \'1\', \'0\', \'{\"menu_type\":\"mega_menu\",\"categories\":[\"10\",\"9\",\"8\",\"7\",\"6\",\"5\",\"4\",\"3\",\"2\",\"1\"]}\', \'2022-12-15 19:35:46\', \'2022-12-15 19:36:00\')';
        
        $this->insert($menuItems);

        Cache::forget("{$tenantSubdomain}_front_header_links");
    }

    private function siteColors(){
        SiteColor::create([
            'css' =>   ':root {
                --top_header_background_color:linear-gradient(to right, #f6f9fd 99%, #f6f9fd 99%);
                --top_header_color:#bed6ed;
                --top_header_a_color:;
                --top_header_a_hover_color:;
                --middle_header_background_color:linear-gradient(to right, #ffffff 99%, #ffffff 99%);
                --middle_header_color:#000000;
                --middle_header_a_color:#000000;
                --middle_header_a_hover_color:#000000;
                --middle_header_search_input_color:#7d90a8;
                --middle_header_search_input_background_color:linear-gradient(to right, #ffffff 44%, #ffffff 44%);
                --middle_header_search_input_border_color:#dfe3e8;
                --middle_header_search_input_border_size:1px;
                --bottom_header_background_color:linear-gradient(to right, #000000 99%, #000000 99%);
                --bottom_header_color:#f2f8ff;
                --bottom_header_a_hover_color:;
                --bottom_header_border_color:#0b0c10;
                --bottom_header_border_size:0;
                --side_menu_background_color:linear-gradient(to right, #ffffff 99%, #ffffff 99%);
                --side_menu_color:#000000;
                --side_menu_a_hover_color:#000000;
                --side_menu_border_color:;
                --side_menu_border_size:0;
                --main_titles_color:#363636;
                --main_titles_a_color:#363636;
                --main_titles_a_hover_color:#45a29e;
                --main_body_main_background_color:linear-gradient(to right, #ffffff 99%, #ffffff 99%);
                --main_body_main_color:#363636;
                --main_body_main_a_color:#363636;
                --main_body_main_a_hover_color:#45a29e;
                --main_body_alternative_background_color:linear-gradient(to right, #f6f9fd 99%, #f6f9fd 99%);
                --main_body_alternative_color:#000000;
                --main_body_alternative_a_color:#999999;
                --main_body_alternative_a_hover_color:#000000;
                --main_body_alternative_a_hover_background_color:linear-gradient(to right, #ffffff 99%, #ffffff 99%);
                --card_titles_color:#1b3b59;
                --card_titles_a_color:#363636;
                --card_titles_a_hover_color:#242d38;
                --card_body_background_color:linear-gradient(to right, #ffffff 99%, #ffffff 99%);
                --card_body_color:#1b3459;
                --card_body_a_color:#03090d;
                --card_body_a_hover_background_color:linear-gradient(to right, #1f2833 99%, #1f2833 99%);
                --card_body_a_hover_color:#242d38;
                --card_body_border_color:#dedede;
                --card_body_border_size:1px;
                --card_body_border_radius:0;
                --top_footer_body_background_color:linear-gradient(to right, #ffffff 1%, #ffffff 99%);
                --top_footer_body_color:#363636;
                --top_footer_body_a_color:#868686;
                --top_footer_body_a_hover_color:#258ec2;
                --bottom_footer_background_color:linear-gradient(to right, #000000 99%, #000000 99%);
                --bottom_footer_color:#ffffff;
                --bottom_footer_a_color:#ffffff;
                --bottom_footer_a_hover_color:#ffffff;
                --footer_social_media_background_color:linear-gradient(to right, #3b3b3b 99%, #3b3b3b 99%);
                --footer_social_media_background_hover_color:linear-gradient(to right, #ffffff 99%, #ffffff 99%);
                --footer_social_media_color:#ffffff;
                --bottom_footer_text_hover_color:#258ec2;
                --button_main_background_color:linear-gradient(to right, #0b73ec 99%, #0b73ec 99%);
                --button_main_background_hover_color:linear-gradient(to right, #3391ea 99%, #3391ea 99%);
                --button_main_color:#ffffff;
                --button_main_text_hover_color:#ffffff;
                --button_main_border_color:;
                --button_main_border_size:0px;
                --button_main_border_rounded:3px;
                --button_secondary_background_color:linear-gradient(to right, #e2f2fd 99%, #e2f2fd 99%);
                --button_secondary_background_hover_color:linear-gradient(to right, #377dff 99%, #377dff 99%);
                --button_secondary_color:#377dff;
                --button_secondary_text_hover_color:#ffffff;
                --button_secondary_border_color:#e5f2fe;
                --button_secondary_border_size:0px;
                --button_secondary_border_rounded:3px;
                --input_color:#000000;
                
                    }',
            'json' => '{
                "--top_header_background_color":"linear-gradient(to right, #f6f9fd 99%, #f6f9fd 99%)",
                "--top_header_color":"#bed6ed",
                "--top_header_a_color":null,
                "--top_header_a_hover_color":null,
                "--middle_header_background_color":"linear-gradient(to right, #ffffff 99%, #ffffff 99%)",
                "--middle_header_color":"#000000",
                "--middle_header_a_color":"#000000",
                "--middle_header_a_hover_color":"#000000",
                "--middle_header_search_input_color":"#7d90a8",
                "--middle_header_search_input_background_color":"linear-gradient(to right, #ffffff 44%, #ffffff 44%)",
                "--middle_header_search_input_border_color":"#dfe3e8",
                "--middle_header_search_input_border_size":"1px",
                "--bottom_header_background_color":"linear-gradient(to right, #000000 99%, #000000 99%)",
                "--bottom_header_color":"#f2f8ff",
                "--bottom_header_a_hover_color":null,
                "--bottom_header_border_color":"#0b0c10",
                "--bottom_header_border_size":"0",
                "--side_menu_background_color":"linear-gradient(to right, #ffffff 99%, #ffffff 99%)",
                "--side_menu_color":"#000000",
                "--side_menu_a_hover_color":"#000000",
                "--side_menu_border_color":null,
                "--side_menu_border_size":"0",
                "--main_titles_color":"#363636",
                "--main_titles_a_color":"#363636",
                "--main_titles_a_hover_color":"#45a29e",
                "--main_body_main_background_color":"linear-gradient(to right, #ffffff 99%, #ffffff 99%)",
                "--main_body_main_color":"#363636",
                "--main_body_main_a_color":"#363636",
                "--main_body_main_a_hover_color":"#45a29e",
                "--main_body_alternative_background_color":"linear-gradient(to right, #f6f9fd 99%, #f6f9fd 99%)",
                "--main_body_alternative_color":"#000000",
                "--main_body_alternative_a_color":"#999999",
                "--main_body_alternative_a_hover_color":"#000000",
                "--main_body_alternative_a_hover_background_color":"linear-gradient(to right, #ffffff 99%, #ffffff 99%)",
                "--card_titles_color":"#1b3b59",
                "--card_titles_a_color":"#363636",
                "--card_titles_a_hover_color":"#242d38",
                "--card_body_background_color":"linear-gradient(to right, #ffffff 99%, #ffffff 99%)",
                "--card_body_color":"#1b3459",
                "--card_body_a_color":"#03090d",
                "--card_body_a_hover_background_color":"linear-gradient(to right, #1f2833 99%, #1f2833 99%)",
                "--card_body_a_hover_color":"#242d38",
                "--card_body_border_color":"#dedede",
                "--card_body_border_size":"1px",
                "--card_body_border_radius":"0",
                "--top_footer_body_background_color":"linear-gradient(to right, #ffffff 1%, #ffffff 99%)",
                "--top_footer_body_color":"#363636",
                "--top_footer_body_a_color":"#868686",
                "--top_footer_body_a_hover_color":"#258ec2",
                "--bottom_footer_background_color":"linear-gradient(to right, #000000 99%, #000000 99%)",
                "--bottom_footer_color":"#ffffff",
                "--bottom_footer_a_color":"#ffffff",
                "--bottom_footer_a_hover_color":"#ffffff",
                "--footer_social_media_background_color":"linear-gradient(to right, #3b3b3b 99%, #3b3b3b 99%)",
                "--footer_social_media_background_hover_color":"linear-gradient(to right, #ffffff 99%, #ffffff 99%)",
                "--footer_social_media_color":"#ffffff",
                "--bottom_footer_text_hover_color":"#258ec2",
                "--button_main_background_color":"linear-gradient(to right, #0b73ec 99%, #0b73ec 99%)",
                "--button_main_background_hover_color":"linear-gradient(to right, #3391ea 99%, #3391ea 99%)",
                "--button_main_color":"#ffffff",
                "--button_main_text_hover_color":"#ffffff",
                "--button_main_border_color":null,
                "--button_main_border_size":"0px",
                "--button_main_border_rounded":"3px",
                "--button_secondary_background_color":"linear-gradient(to right, #e2f2fd 99%, #e2f2fd 99%)",
                "--button_secondary_background_hover_color":"linear-gradient(to right, #377dff 99%, #377dff 99%)",
                "--button_secondary_color":"#377dff",
                "--button_secondary_text_hover_color":"#ffffff",
                "--button_secondary_border_color":"#e5f2fe",
                "--button_secondary_border_size":"0px",
                "--button_secondary_border_rounded":"3px",
                "--input_color":"#000000"
                    }'
        ]);
    }
}
