<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('world.migrations.currencies.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->json('name');
			$table->string('code');
			$table->tinyInteger('precision')->default(2);
			$table->string('symbol');
			$table->string('symbol_native');
			$table->tinyInteger('symbol_first')->default(1);
			$table->string('decimal_mark', 1)->default('.');
			$table->string('thousands_separator', 1)->default(',');

            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists(config('world.migrations.currencies.table_name'));
	}
}
