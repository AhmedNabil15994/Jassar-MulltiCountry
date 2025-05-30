<?php

use Modules\Course\Entities\Note;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Package\Entities\Package;

class CreateOrderOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_offer', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\Modules\Package\Entities\Offer::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('has_offer')->default(false);
            $table->decimal('offer_price', 9, 3)->nullable();
            $table->decimal('total', 9, 3);
            $table->dateTime('expired_date')->nullable();
            $table->integer('period')->nullable();
            $table->longText('selected_products')->nullable();
            $table->schemalessAttributes('settings');
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
        Schema::dropIfExists('order_offer');
    }
}
