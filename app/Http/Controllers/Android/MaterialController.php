<?php
namespace App\Http\Controllers\Android;

use App\Models\Android\Enrollment;
use App\Models\Android\Submits;
use App\Models\Android\SubmitsTestCase;
use App\Models\Android\SubmitsFinalSubmission;
use App\Models\Android\Task;
use App\Models\Android\Task_waiting;
use App\Models\Android\Topic;
use App\Models\Android\Testcase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index() {

        $topic = DB::table("android_topics");
        $data_keseluruhan = array();
        $id = Auth::user()->id;

        $recent_task = array();

        if ( $topic->count() > 0 ) {

            foreach ( $topic->get() AS $isi ){

                // init
                $status = "enroll";
                $progress = 0;
                $grade = "-";
                $total_task = Task::where("android_topic_id", $isi->id)->count();
                $recent = "-";
                $akses = "-";

                $where = array(

                    'android_topic_id'  => $isi->id,
                    'user_id'           => $id
                );

                // check
                $enrollment = DB::table("android_enrollment")->where($where);
                if ( $enrollment->count() != 0 ) {

                    $dt_enroll = $enrollment->first();
                    $status = $dt_enroll->status;

                    $akses = $enrollment->first()->created_at;
                }

                // check progress
                if ( $status != "cancel" ) {

                    $task_waiting = Task_waiting::where( $where );

                    $total_task_waiting = $task_waiting->count();
                    if ( $total_task_waiting > 0 ) {

                        $info = Task::find( $task_waiting->first()->android_task_id );
                        $progress = $total_task_waiting / $total_task * 100;

                        $recent = $info->task_name;
                    }
                }


                $NA = 0;
                if ( $status == "complete" ) {

                    $where_enroll = array(

                        'user_id'           => Auth::user()->id,
                        'android_topic_id'  => $isi->id
                    );
                    $enrollment = Enrollment::where($where_enroll)->first();


                    $dt_all_submit = array();
                    $where = array(

                        'user_id'   => Auth::user()->id, // 1577
                        'android_submits.android_topic_id'  => $isi->id // 11
                    );
                    $submits = Submits::select('android_submits.id','android_task.task_name', 'android_submits.created_at', 'android_submits.duration')
                                ->join('android_task', 'android_task.id', '=', 'android_submits.android_task_id')->where($where)->get();


                    //mulai sini autograding
                    
                    $estimate = 0;
                    $total_submit = $submits->count();

                    if ( $submits->count() > 0 ) {

                        $total_test = 0;

                        foreach ( $submits AS $isi_sb ) {

                            $submit_id = $isi_sb->id;


                            $where_passed = [

                                'android_submit_id' => $isi_sb->id,
                                'status_validate'   => "passed"
                            ];
                            $test_passed = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where($where_passed)->get();

                            // ambil informasi data task untuk memanggil bobot
                            $info_testcase = Testcase::where("task_id", $isi_sb->android_task_id)->get();

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
                                // echo "masuk";
                            }



                            // ----

                            // $dt_submit_testcase = array();

                            // $testcase = SubmitsTestCase::where("android_submit_id", $submit_id)->get();

                            // // autograding
                            // $passed = 0;
                            // foreach ( $testcase AS $det ) {

                            //     if ( $det->status_validate == "passed" ) {

                            //         $passed++;
                            //     }
                            // }

                            // $persentage = ($passed / $testcase->count() * 100);
                            $total_test += $nilai; 
                            $estimate += $isi_sb->duration;


                            // echo $nilai.'<br>';
                        }

                        $NA = $total_test / $total_submit;


                        // echo $total_test.' - '. $total_submit;
                        // echo $NA;
                    }
                }










                $isi->status_waiting = $status;
                $isi->progress       = $progress;
                $isi->grade          = $grade;
                $isi->total_task     = $total_task;
                $isi->akses_materi   = $akses;
                $isi->recent         = $recent;
                $isi->NA             = $NA;




                // recent task 
                $where = array(

                    'android_task.android_topic_id'  => $isi->id,
                    'user_id'           => $id
                );
                $recent_task = Task_waiting::select("task_name", "title")
                        ->join("android_task", "android_task.id", "=", "android_task_waiting.android_task_id")
                        ->join("android_topics", "android_topics.id", "=", "android_task_waiting.android_topic_id")
                        ->where( $where )->orderBy('android_task.created_at', 'desc')->get()->unique('android_topic_id');

                array_push( $data_keseluruhan, $isi );
            }
        }

        
        return view('android.student.material.index', compact('data_keseluruhan', 'recent_task'));
    }



    // upload
    public function upload( Request $request ) {

        $request->validate([

            'file'  => 'required'
        ]);

        $fileName = time().'-'.request()->file->getClientOriginalExtension();
        request()->file->move(public_path('files'), $fileName);

        return request()->json([
            'success'   => "You have successfully upload file"
        ]);
    }

    public function task( Request $request, $topic_id ) {

        // ambil data informasi topik 
        $topic = Topic::findOrFail( $topic_id );
        $where = array(
            'user_id'           => Auth::user()->id,
            'android_task_id'   => $request->id 
        );
        $submit_byId = Submits::where($where);
        $data = [];
        

        // cek memiliki id atau tidak
        if ($request->filled('id')) {

            $id_task_recommend = $request->id;
            
            // ambil informasi data task berdasarkan id 
            $task = Task::findOrFail($id_task_recommend);
            $testcase = Testcase::where("task_id", $id_task_recommend)->get();

            // $task = Task::where('id', $id_task_recommend);


            $submit_information = array();
            $submit_testcase = array();
            if ( $submit_byId->count() > 0 ) {

                $submits = $submit_byId->first();

                $submitTestCase = SubmitsTestCase::where("android_submit_id", $submits->id)->get();

                $submit_information = $submits;
                $submit_testcase = $submitTestCase;


                // echo json_encode($submits->id);
            }


            // cek apakah sudah mengakses materi ? 
            


            // next material 
            $next = Task::where('id', '>', $task->id);

            if ( $next->count() > 0 ) {

                $url = '/android23/task/'.$topic_id.'?id='. $next->min('id');
            } else {

                $url = '/android23/task/'.$topic_id.'?id='. $id_task_recommend.'&type=final';
            }


            $dt_task = array();
            $tasks = Task::where("android_topic_id", $topic_id)->orderBy('task_no', 'ASC')->get();

            foreach ( $tasks AS $isi_tsk ) {

                $where = [

                    'user_id'   => Auth::user()->id,
                    'android_topic_id'  => $topic_id,
                    'android_task_id'   => $isi_tsk->id,

                ];
                $task_waiting = Task_waiting::where($where)->get();

                if ( $task_waiting->count() == 0 ) {

                    $isi_tsk->status_akses = false;
                } else {

                    $isi_tsk->status_akses = true;
                }

                array_push( $dt_task, $isi_tsk );
            }


            $data = array(

                'topic_id'  => $topic_id,
                'id'        => $id_task_recommend,
                'task'      => $task,
                'testcase'  => $testcase,
                'topic'     => $topic,
                'all_task'  => $dt_task,
                'submit_information'    => $submit_information,
                'submit_testcase'       => $submit_testcase,
                'request'   => $request,
                'taskwaiting' => $task_waiting->count(),
                'urlku'        => $url
            );

            // echo $url;



            // cek apabila request sudah final
            if ( $request->type == "final" ) {

                $where_enroll = array(

                    'user_id'   => Auth::user()->id,
                    'android_topic_id'  => $topic_id
                );
                $enrollment = Enrollment::where($where_enroll)->first();


                $dt_all_submit = array();
                $where = array(

                    'user_id'   => Auth::user()->id, // 1577
                    'android_submits.android_topic_id'  => $topic_id // 11
                );
                $submits = Submits::select('android_task_id','android_submits.id','android_task.task_name', 'android_submits.created_at', 'android_submits.duration')
                            ->join('android_task', 'android_task.id', '=', 'android_submits.android_task_id')->where($where)->get();


                //mulai sini autograding
                $NA = 0;
                $estimate = 0;
                $total_submit = $submits->count();

                if ( $submits->count() > 0 ) {

                    $total_test = 0;

                    foreach ( $submits AS $isi ) {

                        $submit_id = $isi->id;
                        $dt_submit_testcase = array();

                        $testcase = SubmitsTestCase::where("android_submit_id", $submit_id)->get();




                        // autograding
                        $where_passed = [

                            'android_submit_id' => $isi->id,
                            'status_validate'   => "passed"
                        ];
                        $test_passed = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where($where_passed)->get();

                        // ambil informasi data task untuk memanggil bobot
                        $info_testcase = Testcase::where("task_id", $isi->android_task_id)->get();

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

                        $total_test += $nilai;


                        // echo $isi->android_task_id.'<br>';




                        // autograding (old)
                        // $passed = 0;
                        // foreach ( $testcase AS $det ) {

                        //     if ( $det->status_validate == "passed" ) {

                        //         $passed++;
                        //     }
                        // }

                        // $persentage = ($passed / $testcase->count() * 100);
                        // $total_test += $persentage; 
                        // total_test = total_NA + persentage
                        // total_test = 0 + 57 
                        // total_test = 57

                        // total_test = total_test + persentnage
                        // total test = 57 + 75
                        $estimate += $isi->duration;

                        array_push( $dt_all_submit, [

                            'info'       => $isi,
                            'persentage' => $nilai,
                            'testcase'   => $testcase
                        ]);
                    }

                    $NA = $total_test / $total_submit;
                }

                $add = array(

                    'submits'   => $dt_all_submit,
                    'NA'    => $NA,
                    'submit_submission' => SubmitsFinalSubmission::all(),
                    'enrollment'        => $enrollment,
                    'estimate'  => $estimate
                );

                // merge
                $data = array_merge($data, $add);
            }

            // return view

        } else {    

            // last recomend
            $id_task_recommend = "";
            $where = [

                'user_id'   => Auth::user()->id,
                'android_topic_id' => $topic_id,
            ];    
            $recommend = Task_waiting::where( $where )->orderBy('created_at', 'DESC');
            if ( $recommend->count() == 0 ) {

                // memberikan id awal
                // ambil materi awal task berdasarkan topic id
                $dt_task = Task::where('android_topic_id', $topic_id)->first();
                $id_task_recommend = $dt_task->id;
            } else {

                $dt_task = $recommend->first();
                $id_task_recommend = $dt_task->android_task_id;
            }

            // echo "test";
            // redirect . ..
        }



        // access materi update
        $cektaskwaiting = array(

            'user_id'   => Auth::user()->id,
            'android_topic_id'  => $topic_id,
            'android_task_id'   => $id_task_recommend
        );


        // cek 
        $cekTask = Task_waiting::where( $cektaskwaiting )->get();
        if ( $cekTask->count() == 0 ) {

            Task_waiting::create( $cektaskwaiting );
        }


        // print_r( $data );
        if ( $request->filled('id') ) {

            return view('android.student/material/task', $data);



        } else {
            return redirect('android23/task/'. $topic_id.'?id='. $id_task_recommend);
        }

        
    }





    //validation result
    public function validation() {

        // informasi login sebagai 
        $id = Auth::user()->id;

        $kondisi = ["user_id" => $id, 'status' => "complete"];
        $enrollment = Enrollment::where($kondisi)->get();

        $dt_keseluruhan = array();

        if ( $enrollment->count() > 0 ) {

            foreach ( $enrollment AS $isi ) {

                // id topik 
                $topic_id = $isi->android_topic_id;
                $topic = Topic::find( $topic_id );

                $where_task = ["android_topic_id" => $topic_id, 'user_id' => $id];
                // $task = Task::where( $where_task )->get();

                // ambil data submits
                $submit = Submits::where($where_task)->get();

                $isi->submit = $submit;
                $isi->topic = $topic;
                // $isi->task = $task;


                array_push( $dt_keseluruhan, $isi );
            }
        }

        // echo "oke";
        return view('android.student.validation.index', compact('dt_keseluruhan'));
    }


    public function validation_detail( $topic_id ) {

                $enrollment = Enrollment::where( "android_topic_id", $topic_id )->first();


                $dt_all_submit = array();
                $where = array(

                    'user_id'   => Auth::user()->id, // 1577
                    'android_submits.android_topic_id'  => $topic_id // 11
                );
                $submits = Submits::select('android_task_id','android_submits.id','android_task.task_name', 'android_submits.created_at', 'android_submits.duration')
                            ->join('android_task', 'android_task.id', '=', 'android_submits.android_task_id')->where($where)->get();


                //mulai sini autograding
                $NA = 0;
                $estimate = 0;
                $total_submit = $submits->count();

                if ( $submits->count() > 0 ) {

                    $total_test = 0;

                    foreach ( $submits AS $isi ) {

                        $submit_id = $isi->id;
                        $dt_submit_testcase = array();

                        $testcase = SubmitsTestCase::where("android_submit_id", $submit_id)->get();




                        // autograding
                        $where_passed = [

                            'android_submit_id' => $isi->id,
                            'status_validate'   => "passed"
                        ];
                        $test_passed = SubmitstestCase::join("android_testcase", "android_testcase.id", "=", "android_submits_testcase.android_testcase_id")->where($where_passed)->get();

                        // ambil informasi data task untuk memanggil bobot
                        $info_testcase = Testcase::where("task_id", $isi->android_task_id)->get();

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

                        $total_test += $nilai;


                        // echo $isi->android_task_id.'<br>';




                        // autograding (old)
                        // $passed = 0;
                        // foreach ( $testcase AS $det ) {

                        //     if ( $det->status_validate == "passed" ) {

                        //         $passed++;
                        //     }
                        // }

                        // $persentage = ($passed / $testcase->count() * 100);
                        // $total_test += $persentage; 
                        // total_test = total_NA + persentage
                        // total_test = 0 + 57 
                        // total_test = 57

                        // total_test = total_test + persentnage
                        // total test = 57 + 75
                        $estimate += $isi->duration;

                        array_push( $dt_all_submit, [

                            'info'       => $isi,
                            'persentage' => $nilai,
                            'testcase'   => $testcase
                        ]);
                    }

                    $NA = $total_test / $total_submit;
                }

                $dt_keseluruhan = array(

                    'submits'   => $dt_all_submit,
                    'NA'    => $NA,
                    'submit_submission' => SubmitsFinalSubmission::all(),
                    'enrollment'        => $enrollment,
                    'estimate'  => $estimate
                );

        return view('android.student.validation.detail', compact('dt_keseluruhan'));
    }




    
}
