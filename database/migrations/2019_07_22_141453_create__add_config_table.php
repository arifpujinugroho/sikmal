<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addconfig', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipe');
            $table->text('konten');
            $table->timestamps();
            
        });

		DB::table('addconfig')->insert(array(
            array('tipe' => 'maxdosen','konten' =>'10'),
            array('tipe' => 'modehapus','konten' => '0'),
        ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addconfig');
    }
}
