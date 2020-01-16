<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipePkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipe_pkm', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('tipe', 50);
            $table->tinyInteger('status_upload')->default(0);
            $table->tinyInteger('status_kemajuan')->default(0);
            $table->tinyInteger('status_akhir')->default(0);
			$table->timestamps();
		});

		DB::table('tipe_pkm')->insert(array(
			array('tipe' => '5 Bidang', 'status_upload' => 1,'status_kemajuan'=> 1, 'status_akhir' => 1),
			array('tipe' => '2 Bidang', 'status_upload' => 0,'status_kemajuan'=> 0, 'status_akhir' => 0),
			array('tipe' => 'PKM GFK', 'status_upload' => 0,'status_kemajuan'=> 0, 'status_akhir' => 0),
			array('tipe' => 'SUG', 'status_upload' => 0,'status_kemajuan'=> 0, 'status_akhir' => 0),
		));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipe_pkm');
    }
}
