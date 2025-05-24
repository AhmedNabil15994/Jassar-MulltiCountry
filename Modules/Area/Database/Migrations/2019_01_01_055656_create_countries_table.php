<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('world.migrations.countries.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('iso2', 2);
            $table->decimal('vat')->default(0);
			$table->json('name');
            $table->json('title')->nullable();
			$table->tinyInteger('status')->default(1);
			$table->tinyInteger('installed')->default(0);

			foreach (config('world.migrations.countries.optional_fields') as $field => $value) {
				if ($value['required']) {
					$table->text($field, $value['length'] ?? null)->nullable();
				}
			}

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
		Schema::dropIfExists(config('world.migrations.countries.table_name'));
	}
}
