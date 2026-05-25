<?php


namespace App\Http\Controllers\PHP\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardUnitControllers extends Controller
{
    //
    public function index(){
        $page_title   = "Persetujuan Hak Cipta";
        $page_link    = "02";
        $content = "";
        $form_upload = 'N';
        return view('phpunit.dashboard', ['content'    => $content,
                                          'page_title' => $page_title,
                                          'page_link'  => $page_link,
                                          'form_upload'  => $form_upload
                                          ])->render();
    }
}
