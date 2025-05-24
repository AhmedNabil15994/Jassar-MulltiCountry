<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedInteger('account_type_id')->after('id');

            $table->foreign('account_type_id')->references('id')->on('account_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_types');

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['account_type_id']);
            $table->dropColumn(['account_type_id']);
        });
    }
}
