<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUangPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uang_pkm', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_file_pkm');
            $table->string('nama_toko')->nullable();
            $table->timestamps();

            $table->foreign('id_file_pkm')->references('id')->on('file_pkm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uang_pkm');
    }
}
