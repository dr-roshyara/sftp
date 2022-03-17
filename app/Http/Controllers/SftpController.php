<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
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
                'downloadIcon'=>asset('images/downloadicon.gif')
        ]);
    }

    /**
     * 
     *  Store the file uploaded and transfer it to the sftp server 
     * 
     * @param: Request $request 
     * @return : return back to the upload form with success message 
     * 
     */
    public function store_sftp_file(Request $request){
        //  dd($request);
         $message  ="";
         $sftp_file  = $request['sftp_file'];
         $filename  ="";
         // dd( 'testing first');
         // dd($sftp_file );
         if($sftp_file){
           $filename =  $this->transfer_file_to_sftp_server($sftp_file);
         }
        //  dd($filename);
          $message  ="Data Was sent successfully. The name of the data sent to server was:  ".$filename;
         return redirect()->back()
         ->with('message', $message ); 
    }

     /***
     * 
     *Save files bzw rechnungen 
     *@param : type: Illuminate\Http\UploadedFile $file 
     *@return: String file name 
     * 
     */

    public function transfer_file_to_sftp_server($file)
    {
        
         /**
          **
          * chek if file is empty 
          * inputtype: $file has a Type :  Illuminate\Http\UploadedFile
          *  
          */


        //  $target_dir = "rechnungen";
         $date = new \DateTime();
        
        /**
         * 
         * basename (/file_name.ext)  gives file_name.ext . 
         * 
         */
            $target_filename = $date->getTimestamp()."_". basename($file->getClientOriginalName());
            $target_filename = preg_replace('/\s+/', '_', $target_filename);
            // dd(Storage::disk('sftp'));
        
        //open the stream of the file         
        $stream = fopen($file->getRealPath(), 'r+');
        
        // get the sftp disk storage and write the file 
        try {
            Storage::disk('sftp')->writeStream($target_filename, $stream,  
           ['filePublic'=> 'public',
           'permPublic' => 0755,
           'directoryPerm' => 0755,
           'visibility' => 'public']);      
            Storage::disk('sftp')->setVisibility($target_filename, 'public');
        }catch (Throwable $exception) {
            dd($exception);
        }
        //close the stream 
        fclose($stream);
    //    dd(Storage::disk('sftp'));
        
        return $target_filename;
    }
}
