<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallcenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callcenter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_callcenter');
            $table->string('nim_callcenter')->nullable();
            $table->string('id_fakultas');
            $table->string('whatsapp');
            $table->string('email_callcenter')->nullable();
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
        Schema::dropIfExists('callcenter');
    }
}
