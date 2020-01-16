<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostDosenController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Admin
    public function __construct()
    {
        //$this->middleware('admin');
        $this->middleware(['dsn', 'sso']);
    }
    ////////////////////////////////////////////////
}
