<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    //
    protected $table ="prodi";

    public function fakultas()
	{
		return $this->belongsTo('App\Fakultas', 'id_fakultas');
	}
}
