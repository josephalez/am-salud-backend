<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_blocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("hour")->unsigned();
            $table->foreign("hour")->references("id")->on("hours")->onDelete("cascade");
            $table->time("start");
            $table->time("end");
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
        Schema::dropIfExists('hour_blocks');
    }
}
