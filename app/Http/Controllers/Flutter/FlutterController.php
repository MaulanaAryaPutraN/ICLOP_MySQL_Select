<?php

namespace App\Http\Controllers\Flutter;


use App\Http\Controllers\Controller;
use App\Models\NodeJS\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Flutter\Topic;
use App\Models\FlutterTestResult;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;

class FlutterController extends Controller
{
    public function index()
    {
        $actual = "";
        $topics = Topic::all();
        $topicsCount = count($topics);

        return view('flutter.student.material.index', [
            'result_up'     => $actual,
            'topics'        => $topics,
            'topicsCount'   => $topicsCount,
        ]);
    }
    function flutter_material_detail()
    {
        $flutterid  = isset($_GET['flutterid']) ? (int)$_GET['flutterid'] : '';
        $start  = isset($_GET['start']) ? (int)$_GET['start'] : '';
        $output = isset($_GET['output']) ? $_GET['output'] : '';
        // dd($start);

        $results = DB::select("select * from flutter_topics_detail where  id_topics = $flutterid and id ='$start' ");
        foreach ($results as $r) {

            if ($flutterid == $r->id_topics) {
                if (empty($r->file_name)) {
                    $contain = $r->description;
                    $pdf_reader = 0;
                } else {
                    $contain = $r->file_name;
                    $pdf_reader = 1;
                }

                $html_start = $this->html_persyaratan($contain, $pdf_reader);
            } else {
                $html_start = "";
            }
        }


        $listTask = DB::select("select aa.*, us.name from flutter_user_submits aa join users us on aa.userid = us.id where flutter_id = $flutterid and flutter_id_topic = $start ");

        $idUser         = Auth::user()->id;
        $roleTeacher    = DB::select("select role from users where id = $idUser");

        $topics = Topic::all();
        $detail = Topic::findorfail($flutterid);
        $topicsCount = count($topics);
        $detailCount = ($topicsCount / $topicsCount) * 10;

        $flutterTestResults = FlutterTestResult::where('user_id', $idUser)->where('flutterid', $flutterid)->first();

        return view('flutter.student.material.topics_detail', [
            'row'        => $detail,
            'topics'     => $topics,
            'flutterid'      => $flutterid,
            'html_start' => $html_start,
            'pdf_reader' => $pdf_reader,
            'topicsCount' => $topicsCount,
            'detailCount' => $detailCount,
            'output'     => $output,
            'flag'       => $results[0] ? $results[0]->flag : 0,
            'listTask'   => $listTask,
            'role'       => $roleTeacher[0] ? $roleTeacher[0]->role : '',
            'flutterTestResults' => $flutterTestResults
        ]);
    }

    public function sendUrl(Request $request)
    {
        $fileURL = $request->input('url');
        // dd($fileURL);
        $response = Http::asForm()->timeout(200000000000)->post('http://localhost:8080/submit', [
            'url' => $fileURL
        ]);

        if ($response) {
            $data = $response->json();
            // Return the data as JSON
            return response()->json($data);
        } else {
            return response()->json(['message' => 'An errorcukihile submitting the request.'], 500);
        }
    }

    function html_start()
    {
        $html = "<div style='text-align:center;font-size:18px'><em>Modul kelas Belajar Pengembangan Aplikasi Android Intermediate dalam bentuk cetak (buku) maupun elektronik sudah didaftarkan ke Dirjen HKI, Kemenkumham RI. Segala bentuk penggandaan dan atau komersialisasi, sebagian atau seluruh bagian, baik cetak maupun elektronik terhadap modul kelas <em>Belajar Pengembangan Aplikasi Android Intermediate</em> tanpa izin formal tertulis kepada pemilik hak cipta akan diproses melalui jalur hukum.</em></div>";
        return $html;
    }

    function html_persyaratan($desc, $pdf_reader)
    {
        $html = $desc;
        return $html;
    }

    function send_task()
    {
        $flutterid  = isset($_GET['flutterid']) ? $_GET['flutterid'] : '';
        $task   = isset($_GET['task']) ? $_GET['task'] : '';
        $output = isset($_GET['output']) ? $_GET['output'] : '';

        $task_db = DB::table('flutter_task')
            ->where('id_topics', $flutterid)
            ->where('id', $task)
            ->first();

        if ($task_db->id == $task) {
            $html_start = $this->html_task();
        } else {
            $html_start = "";
        }

        $pdf_reader = 0;
        $topics = Topic::all();
        $detail = Topic::findorfail($flutterid);
        $topicsCount = count($topics);
        $persen = ($topicsCount / $topicsCount) * 10;
        session(['params' => $persen]);

        return view('flutter.student.material.topics_detail', [
            'row'        => $detail,
            'topics'     => $topics,
            'flutterid'      => $flutterid,
            'html_start' => $html_start,
            'pdf_reader' => $pdf_reader,
            'detailCount' => $persen,
            'output'     => $output,
        ]);
    }

    public function store(Request $request)
    {
        // dd(gettype($request->success_tests));
        $user_id = Auth::user()->id;

        // Validasi request jika diperlukan
        $request->validate([
            'success_tests' => 'required',
            'failed_tests' => 'required',
            'score' => 'required|numeric',
        ]);


        // Simpan data ke dalam database menggunakan model
        $testResult = FlutterTestResult::create([
            'user_id' => $user_id,
            'success_tests' => $request->success_tests,
            'failed_tests' => $request->failed_tests,
            'score' => $request->score,
            'flutterid' => $request->flutterid,
        ]);

        // Jika ingin memberikan respons atau melakukan sesuatu setelah penyimpanan
        return response()->json(['message' => 'Data berhasil disimpan.', 'data' => $testResult]);
    }

    // function html_task(){
    //     return view('php.student.task.form_submission_task',[]);
    // }

    // function php_admin(){
    //     return view('php.admin.material.upload_materi',[]);

    // }
}
