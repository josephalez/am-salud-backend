<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_payments', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string("name",32);
            $table->string("description",255)->nullable();
            $table->timestamps();
        });



        Schema::create('worker_prices', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger("worker")->unsigned();
            $table->foreign("worker")->references("id")->on("users")->onDelete("cascade");

            $table->double('tarifa',10,2);
            $table->string('descripcion',70);

            $table->tinyInteger('minutes');
            
            $table->timestamps();
        });




        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("user")->unsigned();
            $table->foreign("user")->references("id")->on("users")->onDelete("cascade");

            $table->bigInteger("worker")->unsigned();
            $table->foreign("worker")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedSmallInteger('payment_id');
            $table->double('monto',10,2);
            $table->double('descuento',10,2);
            $table->double('total',10,2);
            $table->datetime("reservation_start");

            $table->datetime("reservation_end");

            $table->boolean("pagado")->default(0);
            $table->boolean("canceled")->default(0);

            $table->enum('status',['pendiente', 'pagada', 'reagendada', 'cancelada'])->default('pendiente');
            
            $table->boolean("confirmed")->default(0);
            $table->string('pago_stripe_id')->nullable();


            $table->unsignedBigInteger('asociado')->nullable();
            $table->foreign("asociado")->references("id")->on("asociados");

            $table->unsignedSmallInteger('servicio_id');
            $table->foreign("servicio_id")->references("id")->on("services");
            $table->foreign('payment_id')->references('id')->on('type_payments');
            $table->timestamps();
        });
        Schema::create('zones_reservations', function (Blueprint $table) {
            $table->id(); 

            $table->bigInteger("reservation")->unsigned();
            $table->foreign("reservation")->references("id")->on("reservations")->onDelete("cascade");

            $table->bigInteger("zone")->unsigned();
            $table->foreign("zone")->references("id")->on("zona_lasers")->onDelete("cascade");

            $table->boolean('is_retoque')->default(0);
            $table->boolean('status')->default(0);

            $table->double('monto_zona',10,2);            

            $table->timestamps();
        });
        Schema::create('contratos',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->string('file_contrato');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->unsignedBigInteger('operador');
            $table->foreign('operador')->references('id')->on('reservations')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('cuestionarios',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->string('data');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
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
        Schema::dropIfExists('zones_reservations');
        Schema::dropIfExists('type_payments');
        Schema::dropIfExists('contratos');
        Schema::dropIfExists('cuestionarios');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('worker_prices');
    }
}
