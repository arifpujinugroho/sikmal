<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pkm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_file_pkm');
            $table->text('alamat_dosen')->nullable();
            $table->string('kontak_dosen')->nullable();
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
        Schema::dropIfExists('detail_pkm');
    }
}
