<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentitasMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identitas_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nim', 11);
            $table->string('nama', 50);
            $table->string('email');
			$table->text('alamat')->nullable();
			$table->enum('jenis_kelamin', ['L','P'])->nullable();
            $table->string('telepon', 15)->nullable();
            $table->string('backup_telepon')->nullable();
            $table->unsignedBigInteger('id_prodi');
            $table->string('tempatlahir')->nullable();
            $table->string('tanggallahir')->nullable();
            $table->string('pasfoto')->nullable();
            $table->enum('ukuranbaju',['S','M','L','XL','XXL','XXXL','XXXXL'])->nullable();
            $table->string('scanktm')->nullable();
            $table->enum('kelengkapan', ['Y', 'N'])->default('N');
            $table->longText('crypt_token')->nullable();
            $table->string('id_user');
            $table->timestamps();

            $table->foreign('id_prodi')->references('id')->on('prodi');
        });

		DB::table('identitas_mahasiswa')->insert(array(
            array('nim' => '16520241009','tempatlahir'=>'Jakarta','tanggallahir'=>'1997-11-07','telepon' =>'6285885994505','nama' => 'Arif Puji Nugroho', 'email' => 'arif.puji2016@student.uny.ac.id', 'id_prodi'=>'13', 'alamat' => 'Yogyakarta','pasfoto'=>'arif.jpg','jenis_kelamin' =>'L','kelengkapan' =>'Y','id_user'=>'2','crypt_token'=>Crypt::encrypt('arif.puji2016@student.uny.ac.id')),
        ));

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identitas_mahasiswa');
    }
}
