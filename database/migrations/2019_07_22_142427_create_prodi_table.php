<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prodi', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('id_fakultas');
			$table->string('nama_prodi', 50);
			$table->string('jenjang_prodi');
			$table->timestamps();

			
            $table->foreign('id_fakultas')->references('id')->on('fakultas');
        });

        /**
		* id fakultas :
		* FT    => 1
		* FE    => 2
		* FIS   => 3
		* FMIPA => 4
		* FIP   => 5
		* FBS   => 6
		* FIK   => 7
		*/
		DB::table('prodi')->insert(array(
			// Fakultas Teknik
			array('id_fakultas' => 1, 'nama_prodi' => 'Tata Rias Dan Kecantikan', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Sipil', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Boga', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Busana', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Elektro', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Elektronika', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Mesin', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Otomotif', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Boga', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Busana', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Elektro', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Elektronika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Informatika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Mesin', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Mekatronika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Otomotif', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Pendidikan Teknik Sipil dan Perencanaan', 'jenjang_prodi' => 'S1'),

			// Fakultas Ekonomi
			array('id_fakultas' => 2, 'nama_prodi' => 'Pendidikan Akuntansi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Akuntansi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Akuntansi', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Manajemen', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Manajement Pemasaran', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Ilmu Administrasi Negara', 'jenjang_prodi' => 'S1'), ///????
			array('id_fakultas' => 2, 'nama_prodi' => 'Pendidikan Administrasi Perkantoran', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Pendidikan Ekonomi', 'jenjang_prodi' => 'S1'),

			// Fakultas Ilmu Sosial
			array('id_fakultas' => 3, 'nama_prodi' => 'Ilmu Sejarah', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 3, 'nama_prodi' => 'Pendidikan Sejarah', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 3, 'nama_prodi' => 'Pendidikan Sosiologi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 3, 'nama_prodi' => 'Sekretari', 'jenjang_prodi' => 'S1'), //??????????
			array('id_fakultas' => 3, 'nama_prodi' => 'Pendidikan Kewarganegaraan', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 3, 'nama_prodi' => 'Pendidikan Geografi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 3, 'nama_prodi' => 'Pendidikan IPS', 'jenjang_prodi' => 'S1'),

			// Fakultas Matematika dan Ilmu Pengetahuan Alam
			array('id_fakultas' => 4, 'nama_prodi' => 'Biologi Internasional', 'jenjang_prodi' => 'S1'), //????
			array('id_fakultas' => 4, 'nama_prodi' => 'Pendidikan Biologi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Biologi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Fisika Internasional', 'jenjang_prodi' => 'S1'), //?????
			array('id_fakultas' => 4, 'nama_prodi' => 'Pendidikan Fisika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Fisika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Kimia Internasional', 'jenjang_prodi' => 'S1'), //??????
			array('id_fakultas' => 4, 'nama_prodi' => 'Pendidikan Kimia', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Kimia', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Matematika Internasional', 'jenjang_prodi' => 'S1'), //????
			array('id_fakultas' => 4, 'nama_prodi' => 'Pendidikan Matematika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'Matematika', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 4, 'nama_prodi' => 'IPA Internasional', 'jenjang_prodi' => 'S1'), //?????
			array('id_fakultas' => 4, 'nama_prodi' => 'Pendidikan IPA', 'jenjang_prodi' => 'S1'),

			// Fakultas Ilmu Pendidikan
			array('id_fakultas' => 5, 'nama_prodi' => 'Bimbingan dan Konseling', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Kebijakan Pendidikan', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Manajemen Pendidikan', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Teknologi Pendidikan', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Pendidikan Luar Biasa', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Pendidikan Luar Sekolah', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Pendidikan Anak Usia Dini', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Pendidikan Guru Sekolah Dasar', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 5, 'nama_prodi' => 'Psikologi', 'jenjang_prodi' => 'S1'),


			// Fakultas Bahasa dan Seni
			array('id_fakultas' => 6, 'nama_prodi' => 'Bahasa dan Sastra Indonesia', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Bahasa dan Sastra Inggris', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa Inggris', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa Jawa', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa Jerman', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa Perancis', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa dan Sastra Indonesia', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Seni Rupa', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Seni Tari', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Kriya', 'jenjang_prodi' => 'S1'), //kriya
			array('id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Seni Musik', 'jenjang_prodi' => 'S1'),


			// Fakultas Ilmu Keolahragaan
			array('id_fakultas' => 7, 'nama_prodi' => 'Ilmu Keolahragaan', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 7, 'nama_prodi' => 'Pendidikan Guru Sekolah Dasar Pendidikan Jasmani', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 7, 'nama_prodi' => 'Pendidikan Jasmani Kesehatan dan Rekreasi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 7, 'nama_prodi' => 'Pendidikan Kepelatihan Olahraga', 'jenjang_prodi' => 'S1'),

			

			// Tambahan -----------------------------------------------------------------------------------------
			// Fakultas Teknik
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Elektro', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Elektronika', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Mesin', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Otomotif', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Sipil', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Boga', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Busana', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Tata Rias Dan Kecantikan', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknologi Informasi', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Elektro', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Manufaktur', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 1, 'nama_prodi' => 'Teknik Sipil', 'jenjang_prodi' => 'S1'),
			
			// Fakultas  Ekonomi
			array('id_fakultas' => 2, 'nama_prodi' => 'Akuntansi', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Pemasaran', 'jenjang_prodi' => 'D4'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Administrasi Perkantoran', 'jenjang_prodi' => 'D4'),

			//Fakultas Sosial
			array('id_fakultas' => 3, 'nama_prodi' => 'Administrasi Publik', 'jenjang_prodi' => 'S1'),
			array('id_fakultas' => 3, 'nama_prodi' => 'Ilmu Komunikasi', 'jenjang_prodi' => 'S1'),

			//MIPA 
			array('id_fakultas' => 4, 'nama_prodi' => 'Statistika', 'jenjang_prodi' => 'S1'),

			//FBS
			array('id_fakultas' => 6, 'nama_prodi' => 'Program BIPA', 'jenjang_prodi' => 'D1'),

			// PPI -----------------------------------------------------------------------------------------------
			array('id_fakultas' => 1, 'nama_prodi' => 'Program Profesi Insinyur', 'jenjang_prodi' => 'PPI'),

			
			array('id_fakultas' => 2, 'nama_prodi' => 'Administrasi Perkantoran', 'jenjang_prodi' => 'D3'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Biologi', 'jenjang_prodi' => 'TK'),
			array('id_fakultas' => 2, 'nama_prodi' => 'Kimia', 'jenjang_prodi' => 'TK'),
		));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodi');
    }
}
