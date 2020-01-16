<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTahunAnggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahun_pkm', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('tahun', 8);
			$table->text('keterangan')->nullable();
			$table->tinyInteger('aktif')->default(0);
			$table->timestamps();
		});

		DB::table('tahun_pkm')->insert(array(
			array('tahun' => date('Y'), 'keterangan' => 'Dibuat otomatis oleh sistem', 'aktif' => 1),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tahun_anggaran');
    }
}
