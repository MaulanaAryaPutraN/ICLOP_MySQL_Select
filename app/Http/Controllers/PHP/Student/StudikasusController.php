<?php

namespace App\Http\Controllers\PHP\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class StudikasusController extends Controller
{
    //
    function index(){
        echo 1;
    }
    ////
    function upload_jawaban(Request $request){
        $this->validate($request, [
            'file' => 'required',
           
        ]);
     
        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('file');
     
              // nama file
        echo 'File Name: '.$file->getClientOriginalName();
        echo '<br>';
     
              // ekstensi file
        echo 'File Extension: '.$file->getClientOriginalExtension();
        echo '<br>';
     
              // real path
        echo 'File Real Path: '.$file->getRealPath();
        echo '<br>';
     
              // ukuran file
        echo 'File Size: '.$file->getSize();
        echo '<br>';
     
              // tipe mime
        echo 'File Mime Type: '.$file->getMimeType();
     
              // isi dengan nama folder tempat kemana file diupload
           
        $file_name = Auth::user()->name.'_'.$file->getClientOriginalName();
        //$file->move($tujuan_upload,$file->getClientOriginalName());
        Storage::disk('local')->makeDirectory('private/'.Auth::user()->name);
        Storage::disk('local')->put('/private/'.Auth::user()->name.'/'.$file_name,File::get($file));
        $userName = Auth::user()->name;
        Session::put('user_name', $userName);
        $user_name = Session::get('user_name');
        $name = "Udjir_GuideA1.html";
        Session::put('sess_path', base_path("storage\app\private\\$user_name\\$name"));

        return redirect('/phpunit/studi-kasus/projects/02')->with('status', 'File Berhasil Diupload!');
    }
    function unittesting(){
        $path_test = base_path("phpunit.xml");
        $path = base_path("vendor\bin\phpunit -c $path_test");
        $output = shell_exec($path);
        echo dd($output);
        //echo json_encode($output);
    }
    function result_test(){
        
        $path_test = base_path("phpunit.xml");
        $path = base_path("vendor\bin\phpunit -c $path_test");
        $output = shell_exec($path);
        // dd($output);
         $string  = json_encode($output);
         $pattern = '/OK \((\d+ test), (\d+ assertion)\)/';
         if (preg_match($pattern, $string, $matches)) {
            $numberOfTest = $matches[0];
            $numberOfTests = $matches[1];
            $numberOfAssertions = $matches[2];
            echo "Status Tests : $numberOfTest\n";
        } else {
            echo "Pattern not found.";
           
        }
    }
    function upload_test(){
       
        $actual = base_path('storage\app\private\Udjir\Udjir_GuideA1.php');
        //$filename = $actual."/temp/$file";
        //include "$filename";
        $php_output = shell_exec("C:\wamp64\bin\php\php7.4.33\php.exe $actual 2>&1");
        //echo $php_output;
    
        
    }
    //
    function projects(string $id){
        $page_title   = "Studi Kasus $id";
        $page_link    = "$id";
        $content = "
        <table>
            <tr>
                <td>
                Buatlah Tampilan Seperti Berikut :
                <br />
                <br />
                </td>
            </tr>
        </table>
        ";
        $form_upload = 'Y';
        return view('phpunit.dashboard', ['content'    => $content,
                                          'page_title' => $page_title,
                                          'page_link'  => $page_link,
                                          'form_upload'=> $form_upload])->with('form_upload','Y');
    }
}
