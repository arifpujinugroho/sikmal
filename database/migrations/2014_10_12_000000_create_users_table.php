<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('level', ['SuperAdmin','Operator','Mahasiswa','Perekap','Dosen']);
            $table->rememberToken();
            $table->timestamps();
        });

		DB::table('users')->insert(array(
			array('username' => 'mimincakep', 'email_verified_at' => new \DateTime(), 'password' => Hash::make('tanyapakmuiz'), 'level' => 'SuperAdmin'),
            array('username' => '16520241009','email_verified_at' => new \DateTime(), 'password' => Hash::make('arif.puji2016@student.uny.ac.id'), 'level' => 'Mahasiswa'),
        ));
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
