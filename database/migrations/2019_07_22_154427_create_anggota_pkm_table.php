<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_pkm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_file_pkm');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->enum('jabatan',['Ketua','Anggota 1','Anggota 2','Anggota 3','Anggota 4']);
            $table->timestamps();

            $table->foreign('id_file_pkm')->references('id')->on('file_pkm');
            
            $table->foreign('id_mahasiswa')->references('id')->on('identitas_mahasiswa'); 



            
            /*
            ***Versi Lama
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_file_pkm');
            $table->unsignedBigInteger('id_mahasiswa_ketua');
            $table->unsignedBigInteger('id_mahasiswa_ang1')->nullable();
            $table->unsignedBigInteger('id_mahasiswa_ang2')->nullable();
            $table->unsignedBigInteger('id_mahasiswa_ang3')->nullable();
            $table->unsignedBigInteger('id_mahasiswa_ang4')->nullable();
            $table->unsignedBigInteger('id_mahasiswa_ang5')->nullable();
            $table->timestamps();

            $table->foreign('id_file_pkm')->references('id')->on('file_pkm');
            
            $table->foreign('id_mahasiswa_ketua')->references('id')->on('identitas_mahasiswa');
            $table->foreign('id_mahasiswa_ang1')->references('id')->on('identitas_mahasiswa');
            $table->foreign('id_mahasiswa_ang2')->references('id')->on('identitas_mahasiswa');
            $table->foreign('id_mahasiswa_ang3')->references('id')->on('identitas_mahasiswa');
            $table->foreign('id_mahasiswa_ang4')->references('id')->on('identitas_mahasiswa');
            $table->foreign('id_mahasiswa_ang5')->references('id')->on('identitas_mahasiswa'); 
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota_pkm');
    }
}
