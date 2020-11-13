<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('worker_prices', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger("worker")->unsigned();
            $table->foreign("worker")->references("id")->on("users")->onDelete("cascade");

            $table->double('tarifa',10,2);

            $table->string('descripcion',70);

            
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::dropIfExists('worker_prices');*/
    }
}
