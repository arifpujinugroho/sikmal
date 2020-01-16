<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilePkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_pkm', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('id_tahun_pkm');
			$table->unsignedBigInteger('id_skim_pkm');
            $table->text('judul');
            $table->text('keyword')->nullable();
            $table->Longtext('abstrak')->nullable();
            $table->string('dana_pkm')->nullable();
            $table->string('durasi')->nullable();
            $table->text('linkurl')->nullable();
            $table->string('self')->nullable();
            $table->string('template')->nullable('N');
            $table->string('file_proposal')->nullable();
            $table->string('time_proposal')->nullable();
            $table->string('revisi_proposal')->nullable();
            $table->string('time_revisi_proposal')->nullable();
            $table->string('file_laporan_kemajuan')->nullable();
            $table->string('time_laporan_kemajuan')->nullable();
            $table->string('file_laporan_akhir')->nullable();
            $table->string('time_laporan_akhir')->nullable();
            $table->string('nidn_nidk')->nullable();
			$table->string('nidn_dosen', 20);
            $table->string('nama_dosen', 50);
            $table->string('kode_pkm')->nullable();
			$table->string('status');
            $table->timestamps();

            
            $table->foreign('id_tahun_pkm')->references('id')->on('tahun_pkm');
            
            $table->foreign('id_skim_pkm')->references('id')->on('skim_pkm');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_pkm');
    }
}
