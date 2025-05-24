<?php

use Modules\User\Entities\User;
use Modules\Package\Entities\Package;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('email')->nullable();
            $table->text('civil_id')->nullable();
            $table->text('username')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->longText('block')->nullable();
            $table->longText('street')->nullable();
            $table->longText('building')->nullable();
            $table->longText('address')->nullable();
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->string('subscription_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_addresses');
    }
}
