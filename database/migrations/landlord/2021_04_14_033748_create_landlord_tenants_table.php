<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('subdomain')->unique();
            $table->string('domain')->unique()->nullable();
            $table->string('database')->unique();
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
        });
    }
}
