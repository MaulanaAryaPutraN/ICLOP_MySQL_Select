<?php

namespace App\Http\Controllers\Android;

use App\Models\Android\Enrollment;
use App\Models\Android\Topic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnrollController extends Controller
{
    public function enroll( Request $request, $topic_id = null ){

        // cek 
        $topik = Topic::findOrFail($topic_id);

        $where = ['android_topic_id' => $topic_id, 'user_id' => Auth::user()->id];
        $enroll_status = Enrollment::where($where)->count();

        if ( $enroll_status == 0 ) {

            $data = array(

                'android_topic_id'  => $topic_id,
                'user_id'           => Auth::user()->id,
                'status'            => "process"
            );
    
            Enrollment::create( $data );
            return redirect()->route('material');

        } else {

            // sebelumnya sudah pernah melakukan enroll 
            return redirect()->route('material')->withErrors('pesan', 'Invalid Enrollment Request');
        }
        
        
    }
}
