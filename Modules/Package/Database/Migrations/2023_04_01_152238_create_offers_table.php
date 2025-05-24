<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->json("title");
            $table->json("description")->nullable();
            $table->json("slug")->nullable();
            $table->boolean("status")->default(true);
            $table->integer("qty")->default(0);
            $table->integer("free_qty")->default(0);
            $table->unsignedInteger("country_id");
            $table->unsignedTinyInteger("sort")->default(2);
            $table->json("settings")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('offers');
    }
}
