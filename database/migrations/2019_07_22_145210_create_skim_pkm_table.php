<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkimPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skim_pkm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_tipe_pkm');
            $table->string('skim_singkat', 10);
            $table->string('skim_lengkap', 50);
            $table->string('minimal_skim')->nullable();
            $table->string('maksimal_skim')->nullable();
			$table->timestamps();
        
            $table->foreign('id_tipe_pkm')->references('id')->on('tipe_pkm');
        });

        DB::table('skim_pkm')->insert(array(

			// Skim 5 bidang
			array('id_tipe_pkm' => 1,'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'PKM-PSH', 'skim_lengkap' => 'PKM Penelitian Sosial Humaniora'),
			array('id_tipe_pkm' => 1,'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'PKM-PE', 'skim_lengkap' => 'PKM Penelitian Eksakta'),
			array('id_tipe_pkm' => 1,'minimal_skim' => '3', 'maksimal_skim' => '5', 'skim_singkat' => 'PKM-T', 'skim_lengkap' => 'PKM Penerapan Teknologi'),
			array('id_tipe_pkm' => 1,'minimal_skim' => '3', 'maksimal_skim' => '5', 'skim_singkat' => 'PKM-K', 'skim_lengkap' => 'PKM Kewirausahaan'),
            array('id_tipe_pkm' => 1,'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'PKM-KC', 'skim_lengkap' => 'PKM Karsa Cipta'),
            array('id_tipe_pkm' => 1,'minimal_skim' => '3', 'maksimal_skim' => '5', 'skim_singkat' => 'PKM-M', 'skim_lengkap' => 'PKM Pengabdian kepada Masyarakat'),
            
            // Skim 2 bidang /PKM KT
			array('id_tipe_pkm' => 2, 'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'PKM-GT', 'skim_lengkap' => 'PKM Gagasan Tertulis'),
            array('id_tipe_pkm' => 2, 'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'PKM-AI', 'skim_lengkap' => 'PKM Artikel Ilmiah'),
            
            //Skim GFK
            array('id_tipe_pkm' => 3, 'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'PKM-GFK', 'skim_lengkap' => 'PKM Gagasan Futuristik Konstruktif'),
            
            //Skim SUG
			array('id_tipe_pkm' => 4, 'minimal_skim' => '3', 'maksimal_skim' => '3', 'skim_singkat' => 'SUG', 'skim_lengkap' => 'Student Union Grand'),
		));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skim_pkm');
    }
}
