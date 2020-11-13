<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string("name",50)->unique();
            $table->string("description")->nullable();
            $table->unsignedBigInteger("sub_id")->nullable();       
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string("main_picture")->nullable();
            $table->string("picture_uno")->nullable();
            $table->string("picture_dos")->nullable();
            $table->string("name");
            $table->string("description");
            $table->unsignedMediumInteger("stock")->default(0);
            $table->double('price',10,2);            
            $table->timestamps();
        });

        Schema::create('products_categoria',function(Blueprint $table){
            $table->unsignedBigInteger("product_id");   
            $table->unsignedBigInteger("categoria_id");   
            $table->timestamps();            
        });

        Schema::create('cars',function(Blueprint $table){
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("product_id");
            $table->unsignedMediumInteger("cantidad");
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('pedidos',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('status')->default(0);
            $table->boolean('sent')->default(0);
            $table->boolean('domicilio')->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->unsignedSmallInteger('payment_id');
            $table->string('pago_stripe_id')->nullable();
            $table->timestamps();

            $table->string('pedido_code')->nullable();
            $table->string('calle')->nullable();
            $table->string('numExt')->nullable();
            $table->string('numInt')->nullable();
            $table->string('cp')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('payment_id')->references('id')->on('type_payments');
        });

        Schema::create('pedidos_detalles',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedMediumInteger('cantidad');
            $table->double('price',10,2);

            $table->timestamps();
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('product_id')->references('id')->on('products');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('pedidos_detalles');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('products_categoria');
    }
}
