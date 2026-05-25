<?php

namespace App\Http\Controllers\PHP\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\PHP\TaskSubmission;
use App\Models\PHP\UserCheck;

class WelcomeController extends Controller
{
   
    function result_test(){
      
        $uname = DB::select("SELECT * FROM users
        WHERE name COLLATE utf8mb4_general_ci IN (SELECT userid FROM php_user_submits) "); 
        
        if (!empty($uname)) {
            $firstUser = reset($uname); // Get the first element of the array
            $sess_name = isset($firstUser->name) ? $firstUser->name : ''; // Access the 'name' property of the first user
        } 
       
            $task = DB::table('php_submits_submission')->where('username', "$sess_name")->first();
            $username = $task->username;
            $userfile = $task->userfile;
            $ket = htmlspecialchars($task->ket);
            
            $actual = storage_path("app/private/{$username}/{$userfile}");
            $php_output = shell_exec("PHP $actual 2>&1");
            $test = str_replace(array("\r\n","\r","\n"," "),"",htmlspecialchars($php_output));
            
            return view('php.student.task.result_submssion_task',[
             'result_up' => $test,
         ]);
        
      // echo "$value == $sess_name";   
        
    }
    function get_session(){
        if (Auth::check()) {
            $value = Auth::user()->name;
            return $value; 
        } 
        
    }

}
