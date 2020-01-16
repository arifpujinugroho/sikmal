<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    //
    protected $table ="fakultas";

    public function prodi()
	{
		return $this->hasMany('App\Prodi', 'id_fakultas');
    }
}
