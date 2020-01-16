<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DetailPKM extends Model
{
    //
    protected $table ="detail_pkm";

    
    public function filePKM()
    {
        return $this->belongsTo('App\FilePKM', 'id_file_pkm');
    }
}
