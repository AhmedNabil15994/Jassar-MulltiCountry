<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_type_id');

            $table->string('name');
            $table->unsignedDecimal('price')->default(0);
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();

            $table->foreign('account_type_id')->references('id')->on('account_types');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedInteger('plan_id')->after('id');

            $table->foreign('plan_id')->references('id')->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id']);
        });
    }
}
