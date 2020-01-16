<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;

class CreateOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_user');
			$table->string('nama_operator', 50);
			$table->unsignedBigInteger('id_fakultas');
            $table->longText('quotes')->nullable();
            $table->string('logo')->nullable();
            $table->text('opt_status')->nullable();
            $table->timestamps();
            
            $table->foreign('id_fakultas')->references('id')->on('fakultas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator');
    }
}
