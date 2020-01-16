<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFakultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fakultas', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('nama_fakultas', 50);
			$table->string('nama_singkat', 5);
            $table->timestamps();
        });

        
		DB::table('fakultas')->insert(array(
			array('nama_fakultas' => 'Fakultas Teknik', 'nama_singkat' => 'FT'),
			array('nama_fakultas' => 'Fakultas Ekonomi', 'nama_singkat' => 'FE'),
			array('nama_fakultas' => 'Fakultas Ilmu Sosial', 'nama_singkat' => 'FIS'),
			array('nama_fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam', 'nama_singkat' => 'FMIPA'),
			array('nama_fakultas' => 'Fakultas Ilmu Pendidikan', 'nama_singkat' => 'FIP'),
			array('nama_fakultas' => 'Fakultas Bahasa dan Seni', 'nama_singkat' => 'FBS'),
			array('nama_fakultas' => 'Fakultas Ilmu Keolahragaan', 'nama_singkat' => 'FIK'),
		));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_fakultas_');
    }
}
