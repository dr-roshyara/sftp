<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class SftpController extends Controller
{
    //
   /**
    * 
    *File Upload function 
    *@param: null 
    *@return: Inertia rendering 
    *
    *
    */
     public function show_sftp_form(){

        return Inertia::render('Sftp/SftpForm' , [
                // 'downloadIcon'=>asset('images/downloadicon.gif')
        ]);
    }
    
}
