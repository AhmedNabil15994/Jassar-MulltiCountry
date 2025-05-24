<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('world.migrations.states.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('title')->nullable();
            $table->bigInteger('country_id')->unsigned();
			$table->json('name');
            $table->integer('status')->default(1);

			foreach (config('world.migrations.states.optional_fields') as $field => $value) {
				if ($value['required']) {
					$table->text($field, $value['length'] ?? null)->nullable();
				}
			}

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
		Schema::dropIfExists(config('world.migrations.states.table_name'));
	}
}
