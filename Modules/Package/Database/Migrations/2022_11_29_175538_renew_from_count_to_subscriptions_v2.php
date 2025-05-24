<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Package\Entities\PackagePrice;

class RenewFromCountToSubscriptionsV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            
            $table->integer("same_pricerenew_times")->default(0);
            $table->integer("max_puse_days")->default(0);
            $table->unsignedBigInteger("package_price_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['same_pricerenew_times','max_puse_days','package_price_id',]);
        });
    }
}
