<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->integer('plan_id')->unsigned();
            $table->integer('tenant_id')->unsigned();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('duration')->nullable();
            $table->float('original_total')->nullable();
            $table->float('discount')->nullable();
            $table->tinyInteger('is_paid')->default(0);
            $table->float('total')->nullable();
            $table->json('extra_attributes')->nullable();
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('plans')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('tenant_id')->references('id')->on('tenants')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
