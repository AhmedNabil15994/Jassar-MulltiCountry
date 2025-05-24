<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Package\Entities\Package;

class CreatePackagePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float("price")->default(0);
            $table->float("offer_percentage")->nullable();
            $table->date("start_offer_date")->nullable();
            $table->date("end_offer_date")->nullable();
            $table->integer("same_pricerenew_times")->default(0);
            $table->integer("max_puse_days")->default(0);
            $table->text("subscribe_duration_desc")->nullable();
            $table->foreignIdFor(Package::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

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
        Schema::dropIfExists('package_prices');
    }
}
