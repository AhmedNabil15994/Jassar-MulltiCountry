<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSiteColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_colors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('json');
            $table->longText('css');
            $table->timestamps();
        });

        DB::table('site_colors')->insert(
            array(
                'json' => file_get_contents(module_path('DeveloperTools', 'site_colors.json')),
                'css' => file_get_contents(public_path('frontend/css/vars.css')),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_colors');
    }
}
