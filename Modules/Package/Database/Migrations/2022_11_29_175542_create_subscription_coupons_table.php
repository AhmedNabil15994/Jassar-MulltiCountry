<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('subscription_id')->nullable();
            $table->bigInteger('coupon_id')->unsigned()->nullable();
            $table->string('code');
            $table->string('discount_type');
            $table->double('discount_percentage')->nullable();
            $table->double('discount_value')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
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
        Schema::dropIfExists('subscription_coupons');
    }
}
