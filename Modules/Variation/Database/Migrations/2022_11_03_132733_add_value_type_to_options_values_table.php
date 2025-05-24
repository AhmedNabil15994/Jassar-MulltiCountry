<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValueTypeToOptionsvaluesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_values', function (Blueprint $table) {
            $table->enum('type',Modules\Variation\Entities\Option::TYPES)->default('text');
            $table->string('color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_values', function (Blueprint $table) {
            $table->dropColumn(["type","color"]);
        });
    }
}
