<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name',50)->unique();
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('credit_id');
            $table->string('nombre');
            $table->boolean('status')->default(1);
            $table->double('bonus',4,2);
            $table->double('points',8,2);
            $table->timestamps();
            $table->foreign('credit_id')->references('id')->on('creditos');
        });

        Schema::create('mis_paquetes',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('payment_id');
            $table->unsignedSmallInteger('credit_id')->nullable();
            $table->unsignedBigInteger('paquete_id')->nullable();
            $table->unsignedBigInteger('worker')->nullable();
            $table->string('pago_stripe_id')->nullable();

            $table->date('vence')->nullable();
            $table->boolean('status')->default(0);


            $table->double('saldo',10,2);
            $table->double('monto',10,2);
            $table->double('restante',10,2);
            $table->timestamps();
            $table->softDeletes();



            $table->foreign('paquete_id')->references('id')->on('paquetes');
            $table->foreign('payment_id')->references('id')->on('type_payments');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('worker')->references('id')->on('users');
            $table->foreign('credit_id')->references('id')->on('creditos');
        });

        $pdo = DB::getPdo();
        $sql="DROP VIEW IF EXISTS moneda_virtual;
    CREATE view moneda_virtual AS
SELECT SUM(saldo - restante) as saldo , user_id, credit_id FROM mis_paquetes WHERE status=0 GROUP BY credit_id,user_id;";
        $pdo->query($sql);
        /*

    CREATE view moneda_virtual AS
SELECT SUM(saldo - restante) as saldo , user_id, credit_id FROM mis_paquetes WHERE status=0 GROUP BY credit_id,user_id;


        */


        //$pdo = DB::connection()->getPdo();
        //$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
/*
$sql1 = "
        CREATE TRIGGER balance_creditos_saldo_udate AFTER UPDATE ON movimiento_creditos 
        FOR EACH ROW 
            BEGIN 
                UPDATE `balance_creditos` SET `saldo` = saldo+(NEW.monto-OLD.monto) WHERE `balance_creditos`.`id` = NEW.balance_id;
            END;

";
$sql2="
        CREATE TRIGGER balance_creditos_saldo_insert AFTER INSERT ON movimiento_creditos
               FOR EACH ROW
               BEGIN
                    UPDATE `balance_creditos` SET `saldo` = saldo+NEW.monto WHERE `balance_creditos`.`id` = NEW.balance_id;
               END
               ";*/

        //$pdo->exec($sql1);
        //$pdo->exec($sql2);

        //DB::select($sql1);
        //DB::select($sql2);

    //Enter your database information here and the name of the backup file
/*        $mysqlDatabaseName =config('database.connections.mysql.database');
        $mysqlUserName =config('database.connections.mysql.username');
        $mysqlPassword =config('database.connections.mysql.password');
        $mysqlHostName =config('database.connections.mysql.host');
        $mysqlImportFilename  = base_path('MyTriggers.sql');
        
        //Please do not change the following points
        //Import of the database and output of the status
        $command='mysql -h ' .$mysqlHostName .' -u ' .$mysqlUserName .' -p ' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
        $output=array();
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        exec($command,$output,$worked);
        switch($worked){
            case 0:
            $out->writeln('The data from the file <b>' .$mysqlImportFilename .'</b> were successfully imported into the database <b>' .$mysqlDatabaseName );

            break;
            case 1:
            $out->writeln("!!!!! Error al ejecutar el comando !!!!!!!!!!!!!!");
            
            $out->writeln($command);//command            

            $out->writeln("!!!!!!!!!!!!!!!!!!!!!!!");
            break;
        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::select('DROP TRIGGER IF EXISTS balance_creditos_saldo_udate;');
        //DB::select('DROP TRIGGER IF EXISTS balance_creditos_saldo_udate;');
        Schema::dropIfExists('moneda_virtual');
        Schema::dropIfExists('creditos');
        Schema::dropIfExists('paquetes');
        Schema::dropIfExists('movimiento_creditos');
    }
}




