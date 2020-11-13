<?php

use App\User;
use App\Models\Asociado;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum("role",User::Role)->default("user");

            $table->unsignedSmallInteger('area')->nullable();
            $table->foreign("area")->references("id")->on("services")->onDelete("cascade");
            $table->string('username',50)->unique()->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('instagram', 32)->nullable();
            $table->string('last_name');
            $table->string('email',64)->unique();
            $table->string('address',128)->nullable();
            $table->string('phone');
            $table->enum('gender',["male","female"]);
            $table->date('birthday')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('topic_firebase',50)->unique();
            $table->rememberToken();
            $table->timestamps();

            $table->softDeletes();
        });


        Schema::create('asociados',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('email');
            $table->string('name');
            $table->date('birthday');
            $table->enum('genero', Asociado::genero);
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('conekta_clients',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('token_laser',150);
            $table->string('token_nutry',150);

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('conekta_card',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('conekta_client_id');
            $table->string('token_card_laser',150);
            $table->string('token_src_laser',150);
            $table->string('token_card_nutry',150);
            $table->string('token_src_nutry',150);


            $table->string("card_number",4);
            $table->string("brand",100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('conekta_client_id')->references('id')->on('conekta_clients');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('asociados');

        Schema::dropIfExists('conekta_clients');
        Schema::dropIfExists('conekta_card');
    }
}
