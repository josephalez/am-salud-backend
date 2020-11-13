<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonesReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('zones_reservations', function (Blueprint $table) {
            $table->id(); 

            $table->bigInteger("reservation")->unsigned();
            $table->foreign("reservation")->references("id")->on("reservations")->onDelete("cascade");

            $table->bigInteger("zone")->unsigned();
            $table->foreign("zone")->references("id")->on("zona_lasers")->onDelete("cascade");

            $table->boolean('is_retoque')->default(0);

            $table->double('monto_zona',10,2);            

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
        //Schema::dropIfExists('zones_reservations');
    }
}
