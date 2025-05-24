<?php

use Modules\User\Entities\User;
use Modules\Package\Entities\Package;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id');
            $table->boolean("from_admin")->default(false);
            $table->boolean("is_default")->index()->default(false);
            $table->double("price")->default(0);
            $table->boolean("is_free")->default(false);
            $table->date("start_at")->nullable();
            $table->date("end_at")->nullable();
            $table->enum('paid', ['pending', 'paid', 'failed']);
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Package::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->uuid("transaction_id")->nullable();
            $table->primary(["id"]);
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
        Schema::dropIfExists('subscriptions');
    }
}
