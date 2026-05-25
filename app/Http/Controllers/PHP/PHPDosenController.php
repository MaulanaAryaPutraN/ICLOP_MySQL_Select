<?php

namespace App\Http\Controllers\PHP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PHP\Topic;
use App\Models\PHP\Topic_detail;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Auth;

class PHPDosenController extends Controller{
   
    function topics(){
        $topics = Topic::all();
        return view('php.teacher.topics',[
                                            'topics'  => $topics,
                                         ]);

    }

    function add_topics( Request $request, $id ){
        $detail = Topic::findorfail($id);
        $results = DB::select('SELECT * FROM php_topics_detail WHERE id_topics = ?', [$id]);
        return view('php.teacher.topics_detail',[
            'results'   => $results,
            'detail'    => $detail->title,
            'id'        => $id,
         ]);
    }

    function simpan( Request $request){
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'required|string|max:255',
            'editor' => 'required|string',
            'id' => 'required|int|max:11',
            'materials' => 'required|file|max:10240', // Example: max 10MB
        ]);
       // Periksa apakah file yang diunggah bukan PDF
        if ($request->file('materials')->getClientOriginalExtension() !== 'pdf') {
            return redirect()->back()->with('error', 'Materi harus berupa file PDF.');
        }
        
       $originName = $request->file('materials')->getClientOriginalName();
       $fileName   = pathinfo($originName, PATHINFO_FILENAME);
       $extension  = $request->file('materials')->getClientOriginalExtension();
       $fileName   = $fileName . '_' . time() . '.' . $extension;
       $new_name   = str_replace(" ",'_',$fileName);
       
       $path = $request->file('materials')->move(public_path('php/document/A1_BASIC_PHP'), $new_name);
       Topic_detail::create([
        'title' => $validatedData['title'],
        'id_topics' => $validatedData['id'],
        'controller' => $validatedData['caption'],
        'description' => $validatedData['editor'],
        'folder_path' => $path, // Save the path to the uploaded file
        'file_name' => $new_name,
         ]);

         $id = $validatedData['id'];
         return Redirect::to("/php/teacher/topics/add/$id")->with('message', 'Data Berhasil Diinputkan');
    }
   
}