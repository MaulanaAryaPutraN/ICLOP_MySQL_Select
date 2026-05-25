<?php

namespace App\Http\Controllers\Android;

use App\Models\Android\Topic;
use App\Models\User;
use App\Models\Android\Task;
use App\Models\Android\Task_waiting;
use App\Models\Android\Enrollment;
use App\Models\Android\SubmitstestCase;
use App\Models\Android\SubmitsFinalSubmission;
use App\Models\Android\Submits;
use App\Models\Android\Testcase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class AndroidController extends Controller
{

    /** 
       - LECTURER
    */
    public function lecturer_material(){

        $dt_keseluruhan = array();
        $topic = Topic::all();

        $notifikasi = $this->notify_validator();

        $total_mhs = 0;

        if ( $topic->count() > 0 ) {

            foreach ( $topic AS $isi ) {

                // count data task 
                $isi->total = Task::where('android_topic_id', $isi->id)->count();

                $total_mhs_bytopik = Enrollment::where('android_topic_id', $isi->id)->whereNotIn('status', ['cancel'])->get()->count();
                $isi->enroll = $total_mhs_bytopik;

                array_push( $dt_keseluruhan, $isi );


                $total_mhs = $total_mhs + $total_mhs_bytopik;
            }
        }

        
        
        return view('android.teacher.material', compact('dt_keseluruhan', 'total_mhs', 'notifikasi'));
    }


    public function lecturer_overview( $id ){

        $topic = Topic::findOrFail( $id );
        $task = Task::where('android_topic_id', $id)->get();


        $dt_enrollment = array();
        $enrollment = Enrollment::where('android_topic_id', $id)->get();

        foreach ( $enrollment As $isi ) {

            // informasi validation
            $total_request = 0;
            $total_validate = 0;
            $user_id = $isi->user->id;



            // ambil informasi data testcase
            $where = array(

                'user_id'               => $user_id,
                'android_topic_id'      => $id
            );
            $submit = Submits::where($where)->get();


            // - - - - - - - -
            $NA = 0;
            $total_submit = $submit->count();

            // - - - - - - - -

            if ( $submit->count() > 0 ) {


                $total_NA = 0;
                foreach ( $submit AS $isi_s ) {

                    // ambil data testcase
                    $SubmitstestCase = SubmitstestCase::where("android_submit_id", $isi_s->id)->get();
                    $isi->testcase = $SubmitstestCase;



                    // auto
                    $where_passed = [

                        'android_submit_id' => $isi_s->id,
                        'status_validate'   => "passed"
                    ];
                    $test_passed = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where($where_passed)->get();

                    // ambil informasi data task untuk memanggil bobot
                    $info_testcase = Testcase::where("task_id", $isi_s->android_task_id)->get();

                    $bobot = 0;
                    foreach ( $info_testcase AS $isiit ) {

                        $bobot += $isiit->score;
                    }

                    $skor = 0;
                    foreach ( $test_passed AS $isitp ) {

                        $skor += $isitp->score;
                    }

                    $nilai = 0;
                    if ( $skor > 0 && $bobot > 0 ) {

                        $nilai = $skor / $bobot * 100;
                    }

                    $total_NA += $nilai;



                    // autograding
                    // $passed = 0;
                    // foreach ( $SubmitstestCase AS $det ) {

                    //     if ( $det->status_validate == "passed" ) {

                    //         $passed++;
                    //     }
                    // }

                    // $total_NA += ($passed / $SubmitstestCase->count() * 100);
                }

                // echo $total_submit;
                $NA = $total_NA / $total_submit;


                $total_request = $submit->count();
                foreach ( $submit AS $det ) {

                    if ( $det->validator == "validated" ) $total_validate++;
                }
            }


            $progress = 0;
            $total_task = Task::where("android_topic_id", $isi->android_topic_id)->count();
            $status = $isi->status;

            // check progress
            if ( $status != "cancel") {

                $task_waiting = Task_waiting::where( $where );
                $total_task_waiting = $task_waiting->count();

                if ( $total_task_waiting > 0 ) {

                    $info = Task::find( $task_waiting->first()->android_task_id );
                    $progress = $total_task_waiting / $total_task * 100;
                }
            }



            $isi->total_request  = $total_request;
            $isi->total_validate = $total_validate;
            $isi->NA             = $NA;
            $isi->progress       = $progress;


            array_push( $dt_enrollment, $isi );
        }

        $data = array(

            'task'  => $task,
            'topic' => $topic,
            'enrollment'    => $dt_enrollment
        );
        // print_r( $Enrollment->user->name );

        return view('android.teacher.overview', $data);
    }


    public function lecturer_waiting() {

        // ambil data 
        $dt_keseluruhan = array();
        $topic = Topic::all();

        $notifikasi = $this->notify_validator();

        $total_mhs = 0;

        if ( $topic->count() > 0 ) {

            foreach ( $topic AS $isi ) {

                // count data task 
                $isi->total = Task::where('android_topic_id', $isi->id)->count();

                $total_mhs_bytopik = Enrollment::where('android_topic_id', $isi->id)->whereNotIn('status', ['cancel'])->get()->count();
                $isi->enroll = $total_mhs_bytopik;

                array_push( $dt_keseluruhan, $isi );


                $total_mhs = $total_mhs + $total_mhs_bytopik;
            }
        }

        /* Cek  */
        $submits = Submits::where('validator', 'process')->get();
        $dt_need_validator = array();
        
        if ( $submits->count() > 0 ) {

            // group by 
            $dt_submit_topic = array();
            foreach ( $submits AS $index => $isi ) {

                if ( $index > 0 ) {

                    $find = in_array($isi->android_topic_id, array_column($dt_submit_topic, 'android_topic_id'));
                    if ( $find == false ) {

                        array_push( $dt_submit_topic, [
                            'android_topic_id'  => $isi->android_topic_id,
                        ]);
                    }

                    continue;
                } else {

                    array_push( $dt_submit_topic, [
                        'android_topic_id'  => $isi->android_topic_id,
                    ]);
                }
            }


            // akumulasi mahasiswa
            foreach ( $dt_submit_topic AS $isi ) {

                $where = array(

                    'android_topic_id'  => $isi,
                    'validator'         => "process"
                );
                $all_mhs = Submits::where($where)->get();
                $jumlah = $all_mhs->count();

                $dt_all_mahasiswa_by_topic = array();
                

                $title = "";
                if ( $jumlah > 0 ) {

                    foreach ( $all_mhs AS $mhs ) {

                        $title = $mhs->topic->title;
                        array_push( $dt_all_mahasiswa_by_topic, $mhs );
                    }
                }







                // push to validator
                array_push( $dt_need_validator, [
                    
                    'android_topic_id'  => $isi,
                    'title'             => $title, 
                    'jumlah'    => $jumlah,
                    'all_mhs'   => $dt_all_mahasiswa_by_topic
                ]);
            }
            
        }

        return view('android.teacher.validator_waiting', compact('notifikasi', 'total_mhs', 'dt_need_validator'));
    }




    // detail task 
    public function lecturer_waiting_preview( $id_submit ) {

        $submit = Submits::findOrFail( $id_submit );
        // $testcase = SubmitstestCase::where("android_submit_id", $id_submit)->->get();
        $testcase = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where("android_submit_id", $id_submit)->select("android_submits_testcase.*", "case", "score")->get();

        // echo json_encode($testcase);
        return view('android.teacher.validator_detail', compact('submit', 'testcase'));
    }



    // do validate
    public function lecturer_do_validate( $id_testcase, $android_submit_id ) {


        // update all status waiting to failed (default)
        $where = array(

            'android_submit_id' => $android_submit_id,
            'status_validate'   => "waiting"
        );
        SubmitstestCase::where($where)->update(['status_validate' => "failed"]);

        $submittestcase = SubmitstestCase::findOrFail( $id_testcase );
        $submit   = Submits::findOrFail( $android_submit_id );

        $submit->validator = "validated";
        $submit->save();


        if ( $submittestcase->status_validate == "failed" ) {
            $submittestcase->status_validate = "passed";
        } else if ( $submittestcase->status_validate == "passed" ) {
            $submittestcase->status_validate = "failed";
        }

        $submittestcase->save();


        // echo $;
        // return "oke";
    }



    public function lecturer_load_point_testcase( $android_submit_id ) {



        $where = [

            'android_submit_id' => $android_submit_id,
            'status_validate'   => "passed"
        ];
        $test_passed = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where($where)->get();


        // ambil data submit berdasarkan id
        $submit = Submits::findOrFail( $android_submit_id );
        $task = Task::findOrFail( $submit->android_task_id );

        $info_testcase = Testcase::where("task_id", $task->id)->get();
        
        $bobot = 0;
        foreach ( $info_testcase AS $isi ) {

            $bobot += $isi->score;
        }


        $skor = 0;
        foreach ( $test_passed AS $isi ) {

            $skor += $isi->score;
        }
        // hitung bobot per testcase 
        $nilai = 0;

        if ( $skor > 0 && $bobot > 0 ) {

            $nilai = $skor / $bobot * 100;
        }
        
        echo json_encode(["point" => number_format($nilai, 2), "bobot" => $bobot, 'skor'    => $test_passed->count()]);


    }




    // overview student
    public function lecturer_overview_student( $topic_id, $user_id ) {

        $enrollment = Enrollment::where('android_topic_id', $topic_id)->first();
        $user = User::findOrFail( $user_id );

        $where = array(
            'android_topic_id'  => $topic_id,
            'user_id'           => $user_id
        );
        $submit = Submits::where( $where )->get();

        $dt_keseluruhan = array();
        $NA = 0;

        if ( $submit->count() > 0 ) {

            $total_NA = 0;
            $total_submit = $submit->count();
            foreach ( $submit AS $isi ) {


                // ambil data info nilai yang passed
                $where_passed = [

                    'android_submit_id' => $isi->id,
                    'status_validate'   => "passed"
                ];
                // $test_passed = SubmitstestCase::where($where_passed)->get();
                $test_passed = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where($where_passed)->get();

                // ambil informasi data task untuk memanggil bobot
                $info_testcase = Testcase::where("task_id", $isi->android_task_id)->get();
        
                $bobot = 0;
                // $dt = [];
                foreach ( $info_testcase AS $isiit ) {

                    $bobot += $isiit->score;
                }

                $skor = 0;
                foreach ( $test_passed AS $isitp ) {

                    $skor += $isitp->score;
                }
                // hitung bobot per testcase 
                $nilai = 0;
                if ( $skor > 0 && $bobot > 0 ) {

                    $nilai = $skor / $bobot * 100;
                }

                // ambil data testcase
                $isi->testcase = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where('android_submit_id', $isi->id)->get();
                $isi->nilai = $nilai;
                $isi->bobot = $bobot;

                // echo $nilai.' = '.($nilai / $bobot * 100);
                // echo "<br>";



                // autograding
                $total_NA += $nilai;
                // echo $nilai;
                // echo "<hr>";

                array_push($dt_keseluruhan, $isi);
            }

            // echo $total_NA;
            // echo " / ";
            // echo $total_submit;
            // echo " = ";
            $NA = $total_NA / $total_submit;
            // echo $total_NA;

        }



        // submission 
        $submission = SubmitsFinalSubmission::where( $where );



        // echo $NA;
        return view('android.teacher.overview_student', compact('enrollment', 'dt_keseluruhan', 'NA', 'user', 'submission'));
    }



    public function lecturer_confirm_student( $topic_id, $user_id, $enroll_id ){

        $enrollment = Enrollment::findOrFail( $enroll_id );

        $enrollment->status = "complete";
        $enrollment->save();

        return redirect("teacher/android23/overview-student/$topic_id/$user_id");
    }


    // notifikasi validator
    public function notify_validator() {

        $dt_notify = array();
        $total = 0;

        // get submit topic
        $submitTopic = Submits::where('validator', 'process')->groupBy('android_topic_id')->get();


        foreach ( $submitTopic AS $isi ) {

            $topic_id = $isi->android_topic_id;
            $hitung = Submits::where('android_topic_id', $topic_id)->where('validator', 'process')->get()->count();

            $total += $hitung;

            $isi->waiting = $hitung;
            array_push( $dt_notify, $isi );
        }


        return [

            'total' => $total,
            'notify'=> $dt_notify
        ];
    }





    //
    public function index() {


        $dt_keseluruhan = array();
        $topic = Topic::all();

        if ( $topic->count() > 0 ) {

            foreach ( $topic AS $isi ) {

                // count data task 
                $isi->total = Task::where('android_topic_id', $isi->id)->count();
                array_push( $dt_keseluruhan, $isi );
            }
        }

        return view('android.admin.topic.index', compact('dt_keseluruhan'));
    }


    // tambah topik
    function add(Request $request) {

        $nama_file = "";
        $directory_upload = "android23/profile";


        // cek direktori
        $direktori = 'android23/document/'.$request->folder_path;
        if ( !is_dir( $direktori ) ){

            mkdir( $direktori );
        }
        
        if ( $request->hasFile('picturePath') ) {

            $file = $request->file('picturePath');
            $nama_file = $file->getClientOriginalName();
            $file->move($directory_upload, $nama_file);
        }


        $data = array(

            'title'         => $request->title,
            'description'   => $request->description,
            'folder_path'   => $request->folder_path,
            'picturePath'   => $nama_file,
            'status'        => $request->status 
        );

        Topic::insert($data);

        return redirect('android23/topic');
    }


    // update topik
    function update( Request $request, $id ) {

        $topik = Topic::where("id", $id)->first();
        $nama_file = $topik->picturePath;

        $directory_upload = "android23/profile";
        
        if ( $request->hasFile('picturePath') ) {

            $file = $request->file('picturePath');
            $nama_file = $file->getClientOriginalName();
            $file->move($directory_upload, $nama_file);


            // delete old pic 
            if ( !empty( $topik->picturePath ) ) {

                unlink( $directory_upload .'/'. $topik->picturePath );
            }
        }


        $data = array(

            'title'         => $request->title,
            'description'   => $request->description,
            'folder_path'   => $request->folder_path,
            'picturePath'   => $nama_file,
            'status'        => $request->status 
        );

        Topic::where('id', $id)->update($data);
        return redirect('android23/topic');
    }


    // delete
    function delete( $id ) {

        Topic::where("id", $id)->delete();
        return redirect('android23/topic');
    }





    // learning task 
    function learning_view( $id ) {

        $topic = Topic::where('id', $id)->first();
        $dt_task = Task::where('android_topic_id', $id)->get();

        // sum bobot keseluruhan 
        // $total = Task::where('android_topic_id', $id)->sum('bobot');
        $total = 0;

        $task = array();
        foreach ( $dt_task AS $isi ) {

            // ambil data testcase
            $testcase = Testcase::where("task_id", $isi->id)->get();
            $isi->testcase = $testcase;

            array_push( $task, $isi );
        }


        // print_r( $total );
        return view('android.admin.topic.learning', compact('topic', 'task', 'total'));
    }


    // tambah learnng
    function learning_add( Request $request, $id, $tipe ) {

        $topic = Topic::where('id', $id)->first();
        $nama_file = "";
        $directory_upload = "android23/document/". $topic->folder_path;
        
        if ( $request->hasFile('material') ) {

            $file = $request->file('material');
            $nama_file = $file->getClientOriginalName();
            $file->move($directory_upload, $nama_file);
        }

        // insert data task
        $data = array(

            'android_topic_id' => $id,
            'task_no'   => $request->task_no,
            'task_name' => $request->title,
            'caption'   => $request->caption,
            'material'  => $nama_file,
            'tipe'      => $tipe,
            // 'testcase'  => implode(',', $tags)
        );
        

        $id_task = Task::create($data)->id;


        // cek apakah mengisi data

        
        $dt_testcase = array();
        if ( $request->has('tags') && $tipe == "submission" ){

            // convert tags to obj
            $data_tags = json_decode($request->tags);

            foreach ( $data_tags AS $val ){

                array_push( $dt_testcase, [

                    'task_id'   => $id_task,
                    'case'      => $val->value,
                    'score'     => 0
                ]);
            } 


            // print_r( $dt_testcase );
            // insert data testcase
            Testcase::insert( $dt_testcase );
        }

        return redirect('android23/topic/learning/'. $id);
    }


    function learning_update( Request $request, $id_topic, $id_task ) {

        $topik = Topic::where("id", $id_topic)->first();
        $task = Task::where("id", $id_task)->first();
        $nama_file = $task->material;

        $directory_upload = "android23/document/$topik->folder_path";
        
        if ( $request->hasFile('material') ) {

            $file = $request->file('material');
            $nama_file = $file->getClientOriginalName();
            $file->move($directory_upload, $nama_file);


            // delete old pic 
            if ( !empty( $task->material ) ) {

                unlink( $directory_upload .'/'. $task->material );
            }   
        }


        $data = array(

            'task_no'   => $request->task_no,
            'task_name' => $request->title,
            'caption'   => $request->caption,
            'material'  => $nama_file,

        );

        Task::where('id', $id_task)->update($data);
        return redirect('android23/topic/learning/'. $id_topic);
    }



    function learning_remove( $id_topic, $id_task ) {

        // remove data testcase
        Testcase::where('task_id', $id_task)->delete();

        Task::where('id', $id_task)->delete();
        return redirect('android23/topic/learning/'. $id_topic);
    }


    /* Testcase */
    public function learning_update_testcase( Request $request ) {

        // task id
        $task_id = $request->task_id;

        $dt_testcase_baru = array();

        foreach ( $request->case AS $index => $isi ) {

            array_push( $dt_testcase_baru, [

                'task_id'   => $task_id,
                'case'      => $isi,
                'score'     => $request->score[$index]
            ]);
        }


        // hapus data testcase lama 
        Testcase::where('task_id', $task_id)->delete();
        Testcase::insert( $dt_testcase_baru );

        return redirect('android23/topic/learning/'. $request->topic_id);
    }


    public function learning_add_testcase( Request $request, $topic_id, $task_id ) {

        $dt_testcase = array();
        if ( $request->has('tags') ){

            // convert tags to obj
            $data_tags = json_decode($request->tags);

            foreach ( $data_tags AS $val ){

                array_push( $dt_testcase, [

                    'task_id'   => $task_id,
                    'case'      => $val->value,
                    'score'     => 0
                ]);
            } 

            // insert data testcase
            Testcase::insert( $dt_testcase );
        }
        return redirect('android23/topic/learning/'. $topic_id);
    }



    public function learning_reset_testcase( $topic_id, $task_id ) {

        Testcase::where('task_id', $task_id)->delete();
        return redirect('android23/topic/learning/'. $topic_id);
    }


    public function learning_remove_testcase( $topic_id, $testcase_id ){

        $testcase = Testcase::find( $testcase_id );
        $testcase->delete();

        return redirect('android23/topic/learning/'. $topic_id);
    }



}
