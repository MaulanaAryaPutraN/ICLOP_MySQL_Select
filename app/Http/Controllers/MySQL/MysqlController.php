<?php

namespace App\Http\Controllers\MySQL;

use App\Http\Controllers\Controller;
use App\Models\MySQL\MySqlTopicDetails;
use App\Models\MySQL\MySqlTopics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class MysqlController extends Controller
{
    public function index()
    {
        $topics = MySqlTopics::all()->map(function($topic) {
            $topic->has_schema = $topic->schema_file_name && $topic->schema_file_path ? true : false;
            return $topic;
        });

        $topicDetails = MySqlTopicDetails::all();
        $topicsCount = count($topics);

        $userId = Auth::user()->id;

        // Ambil semua enroll user (riwayat sesi)
        $allEnrolls = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();

        $studentSubmissions = collect();

        foreach ($allEnrolls as $enroll) {
            // Cek apakah ada data di mysql_student_submissions untuk enroll_id ini
            $submissionData = DB::table('mysql_student_submissions')
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
                ->where('mysql_student_submissions.user_id', $userId)
                ->where('mysql_student_submissions.enroll_id', $enroll->id)
                ->groupBy(
                    'mysql_topics.title',
                    'users.name',
                    'mysql_student_topic_times.duration_seconds',
                    'mysql_topics.id',
                    'mysql_student_submissions.enroll_id'
                )
                ->orderBy('Time', 'desc')
                ->first();

            if ($submissionData) {
                // Jika ada data di mysql_student_submissions, tambahkan data asli
                $totalSoal = $submissionData->TotalSoal ?? 0;
                $submissionData->Score = ($totalSoal > 0) ? floor(($submissionData->Benar / $totalSoal) * 100) : 0;
                $submissionData->EnrollId = $enroll->id;
                $submissionData->EnrollStart = $enroll->started_at;
                $studentSubmissions->push($submissionData);
            } else {
                // Jika tidak ada data di mysql_student_submissions, tambahkan data dummy
                $studentSubmissions->push((object)[
                    'SubmissionTopic' => DB::table('mysql_topics')->where('id', $enroll->topic_id)->value('title'),
                    'Time' => $enroll->started_at,
                    'UserName' => Auth::user()->name,
                    'Benar' => 0,
                    'Salah' => 0,
                    'TotalJawaban' => 0,
                    'Durasi' => null,
                    'TotalSoal' => DB::table('mysql_topic_details')->where('topic_id', $enroll->topic_id)->sum('total_question'),
                    'Score' => 0,
                    'EnrollId' => $enroll->id,
                    'EnrollStart' => $enroll->started_at,
                ]);
            }
        }

        $role = DB::select("select role from users where id = $userId");

        return view(
            'mysql_dml.student.material.index',
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
