<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Package\Entities\Subscription;

class CreateSuspensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspensions', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignIdFor(Subscription::class)->constrained()->cascadeOnDelete();
            $table->date('start_at');
            $table->date('end_at');
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
        Schema::dropIfExists('suspensions');
    }
}
