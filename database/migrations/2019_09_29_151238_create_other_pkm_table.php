<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_pkm', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_tahun_pkm')->nullable();
            $table->string('id_skim_pkm')->nullable();
            $table->string('nama_peneliti')->nullable();
            $table->string('univ')->nullable();
            $table->text('judul')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_pkm');
    }
}
