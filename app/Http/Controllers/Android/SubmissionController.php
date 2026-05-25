<?php

namespace App\Http\Controllers\Android;

use App\Models\Android\Submits;
use App\Models\Android\SubmitsTestCase;
use App\Models\Android\Enrollment;
use App\Models\Android\SubmitsFinalSubmission;
use App\Models\Android\Topic;
use App\Models\Android\Task;
use App\Models\Android\Testcase;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function proses_tambah( Request $request ) {

        $request->validate([

            'userfile'  => 'required|image',
            'duration'  => 'required',
            'comment'   => 'required|string'
        ]);
        
        $file = $request->file('userfile');
        $extension  = $file->getClientOriginalExtension();

        // upload path
        $upload_path = "android23/submission";
        $fileName = uniqid().'-'.strtotime("now").'.'.$extension;
        
        $file->move($upload_path, $fileName);
        $task_id = $request->android_task_id;
        $topic_id = $request->android_topic_id;
        $data = array(

            'user_id'           => Auth::user()->id,
            'android_topic_id'  => $topic_id,
            'android_task_id'   => $task_id,
            'duration'          => $request->duration,
            'upload'            => $fileName,
            'comment'           => $request->comment,
            'validator'         => "process"
        );

        $submit = Submits::create( $data );

        $dt_task = Task::findOrFail( $task_id );
        $dt_testcasefromtask = Testcase::where("task_id", $task_id)->get();
        

        // listdata test case
        $data_submit = array();
        
        if ( $request->has('task') ) {
            foreach ( $dt_testcasefromtask AS $isi ) {

                $status = false;
                foreach ( $request->task AS $ts ){

                    if ( $ts == $isi->id ) {

                        $status = true;
                        break;
                    }
                }
                
                $label = "failed";
                if ( $status ) {

                    $label  = "passed";
                }


                array_push($data_submit, [
                    'user_id'           => Auth::user()->id,
                    'android_submit_id'  => $submit->id,
                    'android_testcase_id'=> $isi->id,
                    'status'=> $label,
                    'status_validate'   => 'waiting',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);
            }

            // print_r( $data_submit );
            SubmitsTestCase::insert($data_submit);
        }
        return redirect('android23/task/'.$topic_id.'?id='.$task_id);
    }


    // submit final submission
    public function submit_final_submission( Request $request, $topic_id ){
        

        if ( !empty( $request->type ) ) {
            // validation 
            if ( $request->type == "github" ) {

                $rules = array(
                    'link'  => 'required|string'
                );
            } else {

                $rules = array(
                    'userfile'  => 'required|file|mimes:zip|max:3072'
                );
            }

            // check rules
            $request->validate($rules);

            if ( $request->type == "zip" ) {

                // file upload
                $file = $request->file('userfile');

                // upload path
                $upload_path = "android23/final-submission";
                $userfile = uniqid().'-'.strtotime("now").'.zip';
                
                $file->move($upload_path, $userfile);
            } else {

                // github
                $userfile = $request->link;
            }



            $data = array(

                'user_id'           => Auth::user()->id,
                'android_topic_id'  => $topic_id,
                'tipe'      => $request->type, 
                'userfile'  => $userfile
            );

            SubmitsFinalSubmission::create( $data );


            $dataEnrollUpdate = array(

                'status'    => "review"
            );
            Enrollment::where(['user_id' => $data['user_id'], 'android_topic_id' => $data['android_topic_id']])->update( $dataEnrollUpdate );

            // redirect
            return redirect('android23/task/'.$topic_id.'?id='.$request->task_id.'&type=final');
        }
    }




    public function overview( $id ) {

        $data = array(
            'topic_id'   => $id,
            'topic'      => Topic::findOrFail( $id ),
             'all_task'  => Task::orderBy('task_no', 'ASC')->get(),
        );

        return view('student.android23.material.overview', $data);
    }
}
