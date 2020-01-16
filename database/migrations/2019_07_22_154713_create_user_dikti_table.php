<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDiktiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_dikti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_file_pkm');
            $table->string('userdikti')->nullable();
            $table->string('pass_dikti')->nullable();
            $table->string('poster')->nullable();
            $table->string('file_ppt')->nullable();
            $table->string('file_artikel')->nullable();
            $table->longText('desc_artikel')->nullable();
            $table->string('laporan_keuangan_uny')->nullable();
            $table->string('laporan_keuangan_dikti')->nullable();
            $table->string('dana_lolos')->nullable();
            $table->string('status_dikti')->nullable();
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
        Schema::dropIfExists('user_dikti');
    }
}
