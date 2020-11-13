<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaserFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laser_forms', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger("reservation")->unsigned();
            $table->foreign("reservation")->references("id")->on("reservations")->onDelete("cascade");
            
            $table->datetime("date");

            $table->boolean('sun')->default(0);
            $table->boolean('medical')->default(0);
            $table->boolean('radiation')->default(0);
            $table->boolean('sensible_skin')->default(0);
            $table->boolean('hormonal')->default(0);
            $table->boolean('external_product')->default(0);
            $table->boolean('menstruation')->default(0);

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
        Schema::dropIfExists('laser_forms');
    }
}
