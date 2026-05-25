<?php

namespace App\Http\Controllers\MySQL;

use App\Http\Controllers\Controller;
use App\Models\MySQL\MySqlTopicDetails;
use App\Models\MySQL\MySqlTopics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MysqlTeacherSubmissionController extends Controller
{
    public function index()
    {
        $topics = MySqlTopics::all();
        $topicDetails = MySqlTopicDetails::all();
        $topicsCount = count($topics);

        $userId = Auth::user()->id;

        // Ambil semua submission dari semua enroll (setiap baris = 1 sesi/enroll)
        $studentSubmissions = DB::table('mysql_student_submissions')
            ->join('users', 'users.id', '=', 'mysql_student_submissions.user_id')
            ->join('mysql_topic_details', 'mysql_topic_details.id', '=', 'mysql_student_submissions.topic_detail_id')
            ->join('mysql_topics', 'mysql_topics.id', '=', 'mysql_topic_details.topic_id')
            ->leftJoin('mysql_student_topic_times', 'mysql_student_topic_times.id', '=', 'mysql_student_submissions.enroll_id')
            ->select(
                DB::raw('MAX(mysql_student_submissions.created_at) as Time'),
                'users.name as UserName',
                'mysql_topics.title as SubmissionTopic',
                DB::raw("SUM(CASE WHEN mysql_student_submissions.status = 'true' THEN 1 ELSE 0 END) as Benar"),
                DB::raw("SUM(CASE WHEN mysql_student_submissions.status = 'false' THEN 1 ELSE 0 END) as Salah"),
                DB::raw("COUNT(mysql_student_submissions.id) as TotalJawaban"),
                'mysql_student_topic_times.duration_seconds as Durasi',
                DB::raw('(SELECT SUM(total_question) FROM mysql_topic_details WHERE topic_id = mysql_topics.id) as TotalSoal'),
                'mysql_student_submissions.enroll_id'
            )
            ->groupBy(
                'mysql_topics.title',
                'users.name',
                'mysql_student_topic_times.duration_seconds',
                'mysql_topics.id',
                'mysql_student_submissions.enroll_id'
            )
            ->orderBy('Time', 'desc')
            ->get();

        // Hitung skor
        foreach ($studentSubmissions as $submission) {
            $totalSoal = $submission->TotalSoal ?? 0;
            $submission->Score = ($totalSoal > 0) ? floor(($submission->Benar / $totalSoal) * 100) : 0;
        }

        $role = DB::select("select role from users where id = $userId");

        return view(
            'mysql_dml.teacher.student_submissions',
            compact(
                'topics',
                'role',
                'topicsCount',
                'topicDetails',
                'studentSubmissions'
            )
        );
    }
}
