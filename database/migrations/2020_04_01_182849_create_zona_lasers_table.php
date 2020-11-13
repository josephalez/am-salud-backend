h<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonaLasersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zona_lasers', function (Blueprint $table) {
            $table->id();
            $table->string('name',40);
            $table->double('completo',15,2);
            $table->double('retoque',15,2);
            $table->time('time_completo',0);
            $table->time('time_retoque',0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zona_lasers');
    }
}
