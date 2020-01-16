<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostPerekapController extends Controller
{
    
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Operator
    public function __construct()
    {
        $this->middleware('rkp');
    }
    ////////////////////////////////////////////////
    
    

}
