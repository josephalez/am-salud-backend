<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("user")->unsigned()->nullable();
            $table->foreign("user")->references("id")->on("users")->onDelete("cascade");

            //$table->bigInteger("worker")->unsigned();
            //$table->foreign("worker")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedSmallInteger("service");
            $table->foreign("service")->references("id")->on("services")->onDelete("cascade");
            $table->float('price');
            $table->unique(['service', 'user']);
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
        Schema::dropIfExists('prices');
    }
}
