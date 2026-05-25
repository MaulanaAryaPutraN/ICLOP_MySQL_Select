<?php

namespace App\Http\Controllers\Select;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Select\SelectStudentController;
use App\Models\Select\SelectTopicDetails;
use App\Models\Select\SelectTopics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SelectTeacherSubmissionController extends Controller
{
    public function index()
    {
        $topics       = SelectTopics::all();
        $topicDetails = SelectTopicDetails::all();
        $topicsCount  = count($topics);
        $userId       = Auth::id();

        // Query 1: enroll yang punya submission tugas
        $withSubmission = DB::table('select_student_submissions')
            ->join('users', 'users.id', '=', 'select_student_submissions.user_id')
            ->join('select_topic_details', 'select_topic_details.id', '=', 'select_student_submissions.topic_detail_id')
            ->join('select_topics', 'select_topics.id', '=', 'select_topic_details.topic_id')
            ->leftJoin('select_student_topic_times', 'select_student_topic_times.id', '=', 'select_student_submissions.enroll_id')
            ->select(
                DB::raw('MAX(select_student_submissions.created_at) as Time'),
                'users.name as UserName',
                'select_topics.title as SubmissionTopic',
                'select_student_submissions.user_id',
                'select_topics.id as topic_id',
                DB::raw("SUM(CASE WHEN select_student_submissions.status = 'true' AND select_student_submissions.submission_type = 'tugas' THEN 1 ELSE 0 END) as Benar"),
                DB::raw("SUM(CASE WHEN select_student_submissions.status = 'false' AND select_student_submissions.submission_type = 'tugas' THEN 1 ELSE 0 END) as Salah"),
                DB::raw("COUNT(CASE WHEN select_student_submissions.submission_type = 'tugas' THEN 1 END) as TotalJawaban"),
                DB::raw("(SELECT SUM(total_tugas) FROM select_topic_details WHERE topic_id = select_topics.id) as TotalSoal"),
                'select_student_submissions.enroll_id'
            )
            ->where('select_student_submissions.submission_type', 'tugas')
            ->groupBy(
                'select_topics.title',
                'users.name',
                'select_topics.id',
                'select_student_submissions.user_id',
                'select_student_submissions.enroll_id'
            )
            ->orderBy('Time', 'desc')
            ->get();

        // Query 2: enroll is_finished=1 yang TIDAK punya submission tugas sama sekali
        $enrolledIds = $withSubmission->pluck('enroll_id')->toArray();

        $noSubmission = DB::table('select_student_topic_times')
            ->join('users', 'users.id', '=', 'select_student_topic_times.user_id')
            ->join('select_topics', 'select_topics.id', '=', 'select_student_topic_times.topic_id')
            ->select(
                'select_student_topic_times.started_at as Time',
                'users.name as UserName',
                'select_topics.title as SubmissionTopic',
                'select_student_topic_times.user_id',
                'select_topics.id as topic_id',
                'select_student_topic_times.id as enroll_id',
                DB::raw("(SELECT SUM(total_tugas) FROM select_topic_details WHERE topic_id = select_topics.id) as TotalSoal")
            )
            ->where('select_student_topic_times.is_finished', 1)
            ->when(!empty($enrolledIds), fn($q) => $q->whereNotIn('select_student_topic_times.id', $enrolledIds))
            ->get()
            ->map(function ($row) {
                $row->Benar        = 0;
                $row->Salah        = 0;
                $row->TotalJawaban = 0;
                return $row;
            });

        // Gabungkan dan proses
        $studentSubmissions = collect();

        foreach ($withSubmission->merge($noSubmission)->sortByDesc('Time') as $s) {
            $enrollDuration    = DB::table('select_student_topic_times')->where('id', $s->enroll_id)->value('duration_seconds');
            $s->Durasi         = $enrollDuration;
            $s->Score          = SelectStudentController::getTopicScore($s->user_id ?? 0, $s->topic_id ?? 0, $s->enroll_id);
            $s->SubtopicScores = $this->buildSubtopicScores($s->user_id ?? 0, $s->topic_id ?? 0, $s->enroll_id);
            // Flag: tidak mengerjakan = tidak ada submission tugas sama sekali
            $s->TidakMengerjakan = ($s->TotalJawaban == 0);
            $studentSubmissions->push($s);
        }

        $role = DB::select("select role from users where id = $userId");

        return view('select.teacher.student_submissions', compact(
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
