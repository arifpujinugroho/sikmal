<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AnggotaPKM extends Model
{
    //
    protected $table ="anggota_pkm";

    
    public function filePKM()
    {
        return $this->belongsTo('App\FilePKM', 'id_file_pkm');
    }
}
