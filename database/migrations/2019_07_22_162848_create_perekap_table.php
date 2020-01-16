<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;

class CreatePerekapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perekap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_user');
			$table->string('nama_perekap', 50);
			$table->unsignedBigInteger('id_fakultas');
            $table->string('kontak', 50);
            $table->longText('activation')->nullable();
            $table->text('rkp_status')->nullable();
            $table->timestamps();
            
            $table->foreign('id_fakultas')->references('id')->on('fakultas');
        });

		DB::table('perekap')->insert(array(
			array('id_user'=>'3', 'nama_perekap' => 'Nama Perekap', 'id_fakultas' => '1', 'kontak' => '999999999','activation' => Crypt::encryptString('perekap')),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perekap');
    }
}
