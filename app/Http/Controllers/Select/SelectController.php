<?php
// =============================================
// File: app/Http/Controllers/Select/SelectController.php
// =============================================
namespace App\Http\Controllers\Select;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Select\SelectStudentController;
use App\Models\Select\SelectTopicDetails;
use App\Models\Select\SelectTopics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
    public function index()
    {
        $topics = SelectTopics::all()->map(function ($topic) {
            $topic->has_schema = $topic->schema_file_name && $topic->schema_file_path;
            return $topic;
        });

        $topicDetails = SelectTopicDetails::all();
        $topicsCount  = count($topics);
        $userId       = Auth::id();

        $allEnrolls = DB::table('select_student_topic_times')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();

        $studentSubmissions = collect();

        foreach ($allEnrolls as $enroll) {
            $submissionData = DB::table('select_student_submissions')
                ->join('users', 'users.id', '=', 'select_student_submissions.user_id')
                ->join('select_topic_details', 'select_topic_details.id', '=', 'select_student_submissions.topic_detail_id')
                ->join('select_topics', 'select_topics.id', '=', 'select_topic_details.topic_id')
                ->leftJoin('select_student_topic_times', 'select_student_topic_times.id', '=', 'select_student_submissions.enroll_id')
                ->select(
                    DB::raw('MAX(select_student_submissions.created_at) as Time'),
                    'users.name as UserName',
                    'select_topics.title as SubmissionTopic',
                    DB::raw("SUM(CASE WHEN select_student_submissions.status = 'true' AND select_student_submissions.submission_type = 'tugas' THEN 1 ELSE 0 END) as Benar"),
                    DB::raw("SUM(CASE WHEN select_student_submissions.status = 'false' AND select_student_submissions.submission_type = 'tugas' THEN 1 ELSE 0 END) as Salah"),
                    DB::raw("COUNT(select_student_submissions.id) as TotalJawaban"),
                    'select_student_topic_times.duration_seconds as Durasi',
                    DB::raw('(SELECT SUM(total_tugas) FROM select_topic_details WHERE topic_id = select_topics.id) as TotalSoal'),
                    'select_student_submissions.enroll_id'
                )
                ->where('select_student_submissions.user_id', $userId)
                ->where('select_student_submissions.enroll_id', $enroll->id)
                ->groupBy(
                    'select_topics.title',
                    'users.name',
                    'select_student_topic_times.duration_seconds',
                    'select_topics.id',
                    'select_student_submissions.enroll_id'
                )
                ->orderBy('Time', 'desc')
                ->first();

            // Ambil duration_seconds langsung dari enroll — selalu akurat
            // karena disimpan oleh finishTopic() dengan rumus countdown - remaining.
            // Jangan pakai Durasi dari join submission (bisa null jika tidak ada submission).
            $enrollDuration = $enroll->duration_seconds ?? null;

            if ($submissionData) {
                $submissionData->Score          = SelectStudentController::getTopicScore($userId, $enroll->topic_id, $enroll->id);
                $submissionData->SubtopicScores = $this->buildSubtopicScores($userId, $enroll->topic_id, $enroll->id);
                $submissionData->EnrollId       = $enroll->id;
                $submissionData->EnrollStart    = $enroll->started_at;
                $submissionData->Durasi         = $enrollDuration; // override dari enroll
                $studentSubmissions->push($submissionData);
            } else {
                $totalSoal = DB::table('select_topic_details')
                    ->where('topic_id', $enroll->topic_id)
                    ->sum('total_tugas');

                $studentSubmissions->push((object)[
                    'SubmissionTopic'  => DB::table('select_topics')->where('id', $enroll->topic_id)->value('title'),
                    'Time'             => $enroll->started_at,
                    'UserName'         => Auth::user()->name,
                    'Benar'            => 0,
                    'Salah'            => 0,
                    'TotalJawaban'     => 0,
                    'Durasi'           => $enrollDuration,
                    'TotalSoal'        => $totalSoal,
                    'Score'            => 0,
                    'SubtopicScores'   => [],
                    'EnrollId'         => $enroll->id,
                    'EnrollStart'      => $enroll->started_at,
                ]);
            }
        }

        $role = DB::select("select role from users where id = $userId");

        return view('select.student.index', compact(
            'topics',
            'role',
            'topicsCount',
            'topicDetails',
            'studentSubmissions'
        ));
    }

    private function buildSubtopicScores(int $userId, int $topicId, int $enrollId): array
    {
        $details = SelectTopicDetails::where('topic_id', $topicId)->orderBy('id')->get();
        $result = [];
        foreach ($details as $detail) {
            if ($detail->total_tugas == 0) continue;
            $result[] = [
                'title' => $detail->title,
                'score' => SelectStudentController::getSubtopicScore($userId, $detail->id, $enrollId),
            ];
        }
        return $result;
    }
}