<?php

namespace App\Http\Controllers\Select;

use App\Http\Controllers\Controller;
use App\Models\Select\SelectTopicDetails;
use App\Models\Select\SelectTopics;
use App\Models\Select\SelectExpectedQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SelectStudentController extends Controller
{
    // ------------------------------------------------------------------
    // Alur per subtopik:
    // 1. Percobaan 1..N (tidak dinilai, bisa retry, harus selesai semua)
    // 2. Tugas 1..N (dinilai, harus selesai semua)
    // Baru bisa pindah ke subtopik berikutnya.
    //
    // State yang dikirim ke view:
    // - $currentPhase : 'percobaan' | 'tugas'
    // - $page         : nomor soal dalam phase tersebut
    // ------------------------------------------------------------------
    public function showTopicDetail(Request $request)
    {
        $mysqlid  = (int) $request->get('mysqlid');
        $start    = (int) $request->get('start');  // topic_detail_id
        $userId   = Auth::id();

        $detail   = SelectTopicDetails::findOrFail($start);
        $topic    = SelectTopics::findOrFail($mysqlid);

        // Enroll / set koneksi
        $this->setupStudentTestingDatabase($userId, $mysqlid);
        $enroll   = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $mysqlid)->where('is_finished', 0)
            ->orderByDesc('id')->first();

        if (!$enroll) {
            $now = now();
            $enrollId = DB::table('select_student_topic_times')->insertGetId([
                'user_id'   => $userId,
                'topic_id'  => $mysqlid,
                'started_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
                'is_finished' => 0,
            ]);
            $enroll = DB::table('select_student_topic_times')->find($enrollId);
        }
        $enrollId = $enroll->id;

        $countdownSeconds = $topic->countdown_seconds ?? 3600;

        // ---------------------------------------------------------------
        // Hitung sisa waktu:
        // Prioritas: gunakan remaining_seconds yang tersimpan saat pause.
        // Jika timer sedang berjalan (belum di-pause), hitung dari elapsed.
        // ---------------------------------------------------------------
        if ($enroll->paused_at !== null && $enroll->remaining_seconds !== null) {
            // Timer di-pause → gunakan remaining_seconds yang sudah tersimpan
            $sisaDetik = max(0, (int) $enroll->remaining_seconds);
        } elseif ($enroll->paused_at !== null) {
            // Paused tapi remaining_seconds belum ada (data lama) → fallback
            $sisaDetik = max(0, $countdownSeconds - ($enroll->elapsed_seconds ?? 0));
        } else {
            // Timer berjalan → hitung dari elapsed + waktu sejak started_at
            $elapsedSejak = now()->diffInSeconds(\Carbon\Carbon::parse($enroll->started_at));
            $totalElapsed = ($enroll->elapsed_seconds ?? 0) + $elapsedSejak;
            $sisaDetik    = max(0, $countdownSeconds - $totalElapsed);
        }

        // Tentukan phase dan page saat ini
        [$currentPhase, $page] = $this->getCurrentPhaseAndPage($detail, $enrollId, $userId, $request);

        // Ambil soal sesuai phase & page
        $question = SelectExpectedQuery::where('topic_detail_id', $start)
            ->where('type', $currentPhase)
            ->where('answer_number', $page)
            ->first();

        // Submission terakhir untuk soal ini
        $lastSubmission = DB::table('select_student_submissions')
            ->where('user_id', $userId)
            ->where('topic_detail_id', $start)
            ->where('submission_type', $currentPhase)
            ->where('answer_number', $page)
            ->where('enroll_id', $enrollId)
            ->orderByDesc('id')->first();

        $lastAnswer = '';
        $lastStatus = null;
        if ($lastSubmission) {
            $lastQuery  = DB::table('select_queries')->where('id', $lastSubmission->query_id)->first();
            $lastAnswer = $lastQuery?->query ?? '';
            $lastStatus = $lastSubmission->status;
        }

        // ---------------------------------------------------------------
        // FITUR HINT — hanya berlaku untuk soal tugas.
        // Hint dianggap "dipakai" jika ada baris submission bertanda
        // khusus '[HINT_PENALTY]' pada soal ini (lihat showHint()).
        // Baris penalti tersebut sengaja TIDAK dihitung sebagai attempt
        // asli, sehingga dipisahkan lewat filter query != '[HINT_PENALTY]'.
        // ---------------------------------------------------------------
        $attemptsReal  = 0;
        $hintUsed      = false;
        $showHintOffer = false;

        if ($currentPhase === 'tugas') {
            $attemptsReal = DB::table('select_student_submissions')
                ->join('select_queries', 'select_queries.id', '=', 'select_student_submissions.query_id')
                ->where('select_student_submissions.user_id', $userId)
                ->where('select_student_submissions.topic_detail_id', $start)
                ->where('select_student_submissions.submission_type', 'tugas')
                ->where('select_student_submissions.answer_number', $page)
                ->where('select_student_submissions.enroll_id', $enrollId)
                ->where('select_queries.query', '!=', '[HINT_PENALTY]')
                ->count();

            $hintUsed = DB::table('select_student_submissions')
                ->join('select_queries', 'select_queries.id', '=', 'select_student_submissions.query_id')
                ->where('select_student_submissions.user_id', $userId)
                ->where('select_student_submissions.topic_detail_id', $start)
                ->where('select_student_submissions.submission_type', 'tugas')
                ->where('select_student_submissions.answer_number', $page)
                ->where('select_student_submissions.enroll_id', $enrollId)
                ->where('select_queries.query', '[HINT_PENALTY]')
                ->exists();

            // Tawarkan hint kalau: sudah gagal > 2 kali, belum benar, dan belum pernah pakai hint
            $showHintOffer = $attemptsReal > 2 && $lastStatus !== 'true' && !$hintUsed;
        }

        // Hitung total soal per phase
        $totalPercobaan = $detail->total_percobaan;
        $totalTugas     = $detail->total_tugas;

        // Progress hanya dari tugas
        $progressPercent = $this->getProgress($userId, $mysqlid, $enrollId);

        // Apakah phase percobaan sudah selesai semua?
        $percobaanDone = $this->isPhaseComplete($userId, $start, 'percobaan', $totalPercobaan, $enrollId);
        // Apakah phase tugas sudah selesai semua?
        $tugasDone = $this->isPhaseComplete($userId, $start, 'tugas', $totalTugas, $enrollId);

        // Data navigasi subtopik
        $rows       = SelectTopicDetails::where('topic_id', $mysqlid)->orderBy('id')->get();
        $detailCount = $rows->count();

        // PDF/modul
        $html_start = '';
        $pdf_reader = 0;
        if (!empty($detail->file_name)) {
            $html_start = $detail->file_name;
            $pdf_reader = 1;
        }

        $topicsNavbar = $topic;
        $topics       = SelectTopics::all()->map(fn($t) => tap($t, fn($t) => $t->has_schema = (bool)($t->schema_file_name && $t->schema_file_path)));
        $isSequential = $topic->is_sequential;

        $isReset = DB::table('select_user_reset')
            ->where('user_id', $userId)->where('topic_id', $mysqlid)->value('is_reset');

        // Jika soal sudah dijawab benar, jalankan query di server agar hasil langsung tampil
        $queryResultHtml = '';
        if ($lastStatus === 'true' && $lastAnswer) {
            $queryResultHtml = $this->runQueryToHtml($lastAnswer, $mysqlid, $userId);
        }

        $viewData = [
            'mysqlid'         => $mysqlid,
            'detail'          => $detail,
            'topicsNavbar'    => $topicsNavbar,
            'topics'          => $topics,
            'rows'            => $rows,
            'detailCount'     => $detailCount,
            'html_start'      => $html_start,
            'pdf_reader'      => $pdf_reader,
            'currentPhase'    => $currentPhase,
            'page'            => $page,
            'totalPercobaan'  => $totalPercobaan,
            'totalTugas'      => $totalTugas,
            'question'        => $question,
            'lastAnswer'      => $lastAnswer,
            'lastStatus'      => $lastStatus,
            'lastSubmission'  => $lastSubmission,
            'percobaanDone'   => $percobaanDone,
            'tugasDone'       => $tugasDone,
            'progressPercent' => $progressPercent,
            'countdownSeconds' => $sisaDetik,
            'enrollId'        => $enrollId,
            'isSequential'    => $isSequential,
            'isReset'         => $isReset,
            'queryResultHtml' => $queryResultHtml,
            'attemptsReal'    => $attemptsReal,
            'hintUsed'        => $hintUsed,
            'showHintOffer'   => $showHintOffer,
        ];

        if ($request->ajax()) {
            // Khusus untuk update modul/PDF saja saat pindah subtopik
            if ($request->get('modul_only')) {
                return response()->json([
                    'pdf_reader' => $pdf_reader,
                    'html_start' => $pdf_reader
                        ? asset('select/modul/' . $html_start)
                        : $html_start,
                    'modul_url'  => $pdf_reader ? asset('select/modul/' . $html_start) : null,
                ]);
            }
            return view('select.student._answer_section', $viewData);
        }

        return view('select.student.topic_detail', $viewData);
    }

    // ------------------------------------------------------------------
    // Tentukan phase dan page berdasarkan state submission student
    // Urutan: percobaan 1..N → tugas 1..N
    // ------------------------------------------------------------------
    private function getCurrentPhaseAndPage(SelectTopicDetails $detail, int $enrollId, int $userId, Request $request): array
    {
        if ($request->has('phase') && $request->has('page')) {
            return [$request->get('phase'), (int) $request->get('page')];
        }

        $totalPercobaan = $detail->total_percobaan;
        $totalTugas     = $detail->total_tugas;
        $percobaanDone = $this->isPhaseComplete($userId, $detail->id, 'percobaan', $totalPercobaan, $enrollId);

        if (!$percobaanDone) {
            for ($i = 1; $i <= $totalPercobaan; $i++) {
                $done = DB::table('select_student_submissions')
                    ->where('user_id', $userId)->where('topic_detail_id', $detail->id)
                    ->where('submission_type', 'percobaan')->where('answer_number', $i)
                    ->where('enroll_id', $enrollId)->where('status', 'true')->exists();
                if (!$done) return ['percobaan', $i];
            }
            return ['percobaan', 1];
        }

        for ($i = 1; $i <= $totalTugas; $i++) {
            $done = DB::table('select_student_submissions')
                ->where('user_id', $userId)->where('topic_detail_id', $detail->id)
                ->where('submission_type', 'tugas')->where('answer_number', $i)
                ->where('enroll_id', $enrollId)->where('status', 'true')->exists();
            if (!$done) return ['tugas', $i];
        }

        return ['tugas', max(1, $totalTugas)];
    }

    private function isPhaseComplete(int $userId, int $topicDetailId, string $type, int $total, int $enrollId): bool
    {
        if ($total == 0) return true;
        $correct = DB::table('select_student_submissions')
            ->where('user_id', $userId)->where('topic_detail_id', $topicDetailId)
            ->where('submission_type', $type)->where('status', 'true')
            ->where('enroll_id', $enrollId)->distinct('answer_number')->count('answer_number');
        return $correct >= $total;
    }

    // ------------------------------------------------------------------
    // SUBMIT
    // ------------------------------------------------------------------
    public function submitUserInput(Request $request)
    {
        $request->validate([
            'userInput'       => 'required|string|max:5000',
            'topic_detail_id' => 'required|integer',
            'mysqlid'         => 'required|integer',
            'submission_type' => 'required|in:percobaan,tugas',
            'answer_number'   => 'required|integer|min:1',
        ]);

        $userInput      = trim($request->input('userInput'));
        $topicDetailId  = $request->input('topic_detail_id');
        $mysqlid        = $request->input('mysqlid');
        $submissionType = $request->input('submission_type');
        $answerNumber   = $request->input('answer_number');
        $userId         = Auth::id();

        $this->setupStudentTestingDatabase($userId, $mysqlid);

        $enroll   = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $mysqlid)->where('is_finished', 0)
            ->orderByDesc('id')->first();
        $enrollId = $enroll?->id;

        $dbName = "db_kuliah";

        $queryId = DB::table('select_queries')->insertGetId([
            'query'      => $userInput,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $expected = SelectExpectedQuery::where('topic_detail_id', $topicDetailId)
            ->where('type', $submissionType)
            ->where('answer_number', $answerNumber)
            ->first();
        $startTime = microtime(true);
        [$status, $feedback] = $this->validateWithMyTap(
            $userInput,
            $dbName,
            $expected?->expected_query ?? null
        );
        $endTime = microtime(true);
        $executionTimeSecond = round($endTime - $startTime, 2);

        Log::info("myTAP execution time: {$executionTimeSecond} seconds with query: {$userInput}");

        $feedbackId = DB::table('select_feedbacks')->insertGetId([
            'query_id'         => $queryId,
            'feedback'         => $feedback,
            'validation_error' => $status === 'false' ? $feedback : null,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        if ($submissionType === 'tugas') {
            $alreadyCorrect = DB::table('select_student_submissions')
                ->where('user_id', $userId)->where('topic_detail_id', $topicDetailId)
                ->where('submission_type', 'tugas')->where('answer_number', $answerNumber)
                ->where('enroll_id', $enrollId)->where('status', 'true')->exists();
            if ($alreadyCorrect) {
                return redirect()->route('v2.showTopicDetail', [
                    'mysqlid' => $mysqlid,
                    'start'   => $topicDetailId,
                    'phase'   => 'tugas',
                    'page'    => $answerNumber,
                ]);
            }
        }

        DB::table('select_student_submissions')->insert([
            'user_id'         => $userId,
            'enroll_id'       => $enrollId,
            'topic_detail_id' => $topicDetailId,
            'submission_type' => $submissionType,
            'answer_number'   => $answerNumber,
            'query_id'        => $queryId,
            'feedback_id'     => $feedbackId,
            'status'          => $status,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect()->route('v2.showTopicDetail', [
            'mysqlid' => $mysqlid,
            'start'   => $topicDetailId,
            'phase'   => $submissionType,
            'page'    => $answerNumber,
        ]);
    }

    // ------------------------------------------------------------------
    // myTAP via PDO
    // ------------------------------------------------------------------
    private function validateWithMyTap(string $userInput, string $dbName, ?string $expectedQuery = null): array
    {
        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port', 3306);
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);

            $stmt = $pdo->prepare('CALL test_select_query(?, ?)');
            $stmt->execute([$userInput, $expectedQuery]);
            $row       = $stmt->fetch(\PDO::FETCH_ASSOC);
            $tapOutput = $row['result'] ?? '';
            $stmt = null;
            $pdo  = null;

            Log::info("myTAP [{$dbName}]: {$tapOutput}");

            if (preg_match('/^ok\s+\d+\s+-/', $tapOutput)) {
                return ['true', 'Congratulations! Your query is correct.'];
            }
            if (preg_match('/not ok\s+\d+\s+-\s+(.+?)$/', $tapOutput, $m)) {
                return ['false', trim($m[1])];
            }
            return ['false', 'Query validation failed.'];
        } catch (\Exception $e) {
            Log::error("myTAP error [{$dbName}]: " . $e->getMessage());
            return ['false', 'myTAP error: ' . $e->getMessage()];
        }
    }


    // ------------------------------------------------------------------
    // Setup koneksi ke db_kuliah (sudah dibuat dosen)
    // ------------------------------------------------------------------
    private function setupStudentTestingDatabase(int $userId, int $topicId): void
    {
        $dbName = "db_kuliah";

        $dbExists = DB::select(
            "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$dbName]
        );

        if (empty($dbExists)) {
            throw new Exception("Database testing belum tersedia. Hubungi dosen.");
        }

        config(['database.connections.mysql_testing.database' => $dbName]);
        DB::purge('mysql_testing');
        DB::reconnect('mysql_testing');
    }

    // ------------------------------------------------------------------
    // Hitung skor satu soal tugas berdasarkan jumlah submit (attempt).
    // Attempt ke-1 benar → 100. Setiap attempt gagal sebelum benar → -10.
    // Minimum skor per soal = 0.
    // ------------------------------------------------------------------
    public static function calcQuestionScore(int $attempts): int
    {
        $wrongBefore = max(0, $attempts - 1);
        return max(0, 100 - ($wrongBefore * 10));
    }

    public static function getSubtopicScore(int $userId, int $topicDetailId, int $enrollId): float
    {
        $detail = SelectTopicDetails::find($topicDetailId);
        if (!$detail || $detail->total_tugas == 0) return 0;

        $totalSoal  = $detail->total_tugas;
        $totalScore = 0;

        for ($i = 1; $i <= $totalSoal; $i++) {
            $isCorrect = DB::table('select_student_submissions')
                ->where('user_id', $userId)
                ->where('topic_detail_id', $topicDetailId)
                ->where('submission_type', 'tugas')
                ->where('answer_number', $i)
                ->where('enroll_id', $enrollId)
                ->where('status', 'true')
                ->exists();

            if ($isCorrect) {
                $attemptCount = DB::table('select_student_submissions')
                    ->where('user_id', $userId)
                    ->where('topic_detail_id', $topicDetailId)
                    ->where('submission_type', 'tugas')
                    ->where('answer_number', $i)
                    ->where('enroll_id', $enrollId)
                    ->count();
                $totalScore += self::calcQuestionScore($attemptCount);
            }
        }

        return round($totalScore / $totalSoal, 2);
    }

    public static function getTopicScore(int $userId, int $topicId, int $enrollId): float
    {
        $details = SelectTopicDetails::where('topic_id', $topicId)->get();
        if ($details->isEmpty()) return 0;

        $subtopicCount = 0;
        $totalScore    = 0;

        foreach ($details as $detail) {
            if ($detail->total_tugas == 0) continue;
            $subtopicCount++;
            $totalScore += self::getSubtopicScore($userId, $detail->id, $enrollId);
        }

        return $subtopicCount > 0 ? round($totalScore / $subtopicCount, 2) : 0;
    }

    // ------------------------------------------------------------------
    // Progress: hanya dari tugas
    // ------------------------------------------------------------------
    private function getProgress(int $userId, int $topicId, int $enrollId): int
    {
        $totalTugas = SelectTopicDetails::where('topic_id', $topicId)->sum('total_tugas');

        $correct = DB::table('select_student_submissions')
            ->join('select_topic_details', 'select_topic_details.id', '=', 'select_student_submissions.topic_detail_id')
            ->where('select_student_submissions.user_id', $userId)
            ->where('select_student_submissions.enroll_id', $enrollId)
            ->where('select_student_submissions.submission_type', 'tugas')
            ->where('select_student_submissions.status', 'true')
            ->where('select_topic_details.topic_id', $topicId)
            ->count();

        return $totalTugas > 0 ? (int) round(($correct / $totalTugas) * 100) : 0;
    }

    public function getStudentProgressAjax(Request $request)
    {
        $userId  = Auth::id();
        $mysqlid = (int) $request->get('mysqlid');
        $enroll  = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $mysqlid)->where('is_finished', 0)
            ->orderByDesc('id')->first();
        return response()->json(['progress' => $this->getProgress($userId, $mysqlid, $enroll?->id ?? 0)]);
    }

    public function sidebarAjax(Request $request)
    {
        $mysqlid  = $request->get('mysqlid');
        $detailId = $request->get('start');
        $userId   = Auth::id();

        $enroll   = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $mysqlid)->where('is_finished', 0)
            ->orderByDesc('id')->first();
        $enrollId = $enroll?->id;

        $progressPercent = $this->getProgress($userId, $mysqlid, $enrollId ?? 0);
        $rows            = SelectTopicDetails::where('topic_id', $mysqlid)->orderBy('id')->get();
        $detailCount     = $rows->count();
        $detail          = SelectTopicDetails::find($detailId);

        return view('select.student.sidebar', compact(
            'mysqlid',
            'detail',
            'progressPercent',
            'detailCount',
            'rows',
            'detailId',
            'enrollId'
        ))->render();
    }

    public function enrollTopic(Request $request)
    {
        $userId  = Auth::id();
        $topicId = $request->input('mysqlid');
        $now     = now();

        $this->setupStudentTestingDatabase($userId, $topicId);

        $activeEnroll = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $topicId)->where('is_finished', 0)
            ->orderByDesc('id')->first();

        $isReset = DB::table('select_user_reset')
            ->where('user_id', $userId)->where('topic_id', $topicId)->value('is_reset');

        if ($activeEnroll && !$isReset) {
            $lastStart = $this->getLastWorkedSubtopic($userId, $topicId, $activeEnroll->id);
            return response()->json(['success' => true, 'enroll_id' => $activeEnroll->id, 'last_start' => $lastStart]);
        }

        $enrollId = DB::table('select_student_topic_times')->insertGetId([
            'user_id'    => $userId,
            'topic_id'   => $topicId,
            'started_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
            'is_finished' => 0,
        ]);

        DB::table('select_user_reset')->updateOrInsert(
            ['user_id' => $userId, 'topic_id' => $topicId],
            ['is_reset' => 0]
        );

        // Enroll baru → mulai dari subtopik pertama
        $firstDetail = DB::table('select_topic_details')
            ->where('topic_id', $topicId)->orderBy('id')->value('id');

        return response()->json(['success' => true, 'enroll_id' => $enrollId, 'last_start' => $firstDetail]);
    }

    // ------------------------------------------------------------------
    // Cari subtopik terakhir yang dikerjakan student (ada submission-nya)
    // Fallback ke subtopik pertama jika belum ada submission sama sekali.
    // ------------------------------------------------------------------
    private function getLastWorkedSubtopic(int $userId, int $topicId, int $enrollId): int
    {
        // Cari topic_detail_id dengan submission terbaru
        $lastSubmission = DB::table('select_student_submissions')
            ->join('select_topic_details', 'select_topic_details.id', '=', 'select_student_submissions.topic_detail_id')
            ->where('select_student_submissions.user_id', $userId)
            ->where('select_student_submissions.enroll_id', $enrollId)
            ->where('select_topic_details.topic_id', $topicId)
            ->orderByDesc('select_student_submissions.id')
            ->value('select_student_submissions.topic_detail_id');

        if ($lastSubmission) {
            return (int) $lastSubmission;
        }

        // Belum ada submission → subtopik pertama
        return (int) DB::table('select_topic_details')
            ->where('topic_id', $topicId)->orderBy('id')->value('id');
    }

    // ------------------------------------------------------------------
    // Pause timer / Heartbeat
    // heartbeat=1 → hanya update remaining_seconds, tidak set paused_at
    // heartbeat=0 → pause penuh (set paused_at)
    // ------------------------------------------------------------------
    public function pauseTimer(Request $request)
    {
        $userId           = Auth::id();
        $topicId          = $request->input('mysqlid');
        $remainingSeconds = $request->input('remaining_seconds');
        $isHeartbeat      = (bool) $request->input('heartbeat', 0);
        $now              = now();

        $enroll = DB::table('select_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();

        if (!$enroll) {
            return response()->json(['success' => false]);
        }

        // Validasi remaining_seconds
        $remaining = ($remainingSeconds !== null && is_numeric($remainingSeconds) && (int) $remainingSeconds >= 0)
            ? (int) $remainingSeconds
            : null;

        if ($isHeartbeat) {
            // Heartbeat: hanya simpan remaining_seconds, jangan ubah paused_at
            // Timer tetap berjalan di client, ini hanya backup ke DB
            if ($remaining !== null) {
                DB::table('select_student_topic_times')->where('id', $enroll->id)->update([
                    'remaining_seconds' => $remaining,
                    'updated_at'        => $now,
                ]);
            }
            return response()->json(['success' => true, 'heartbeat' => true]);
        }

        // Pause penuh — sudah paused sebelumnya, skip
        if ($enroll->paused_at) {
            // Update remaining_seconds saja jika ada nilai baru
            if ($remaining !== null) {
                DB::table('select_student_topic_times')->where('id', $enroll->id)->update([
                    'remaining_seconds' => $remaining,
                    'updated_at'        => $now,
                ]);
            }
            return response()->json(['success' => true, 'already_paused' => true]);
        }

        // Akumulasi elapsed sejak started_at terakhir
        $elapsedSejak = $now->diffInSeconds(\Carbon\Carbon::parse($enroll->started_at));
        $totalElapsed = ($enroll->elapsed_seconds ?? 0) + $elapsedSejak;

        $updateData = [
            'elapsed_seconds' => $totalElapsed,
            'paused_at'       => $now,
            'updated_at'      => $now,
        ];

        if ($remaining !== null) {
            $updateData['remaining_seconds'] = $remaining;
        }

        DB::table('select_student_topic_times')->where('id', $enroll->id)->update($updateData);

        return response()->json(['success' => true, 'elapsed_seconds' => $totalElapsed]);
    }

    // ------------------------------------------------------------------
    // Resume timer (student kembali ke halaman)
    // Mengembalikan remaining_seconds yang tersimpan saat pause.
    // ------------------------------------------------------------------
    public function resumeTimer(Request $request)
    {
        $userId  = Auth::id();
        $topicId = $request->input('mysqlid');
        $now     = now();

        $enroll = DB::table('select_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();

        if (!$enroll) {
            return response()->json(['success' => false, 'message' => 'No active session.']);
        }

        $countdownSeconds = DB::table('select_topics')->where('id', $topicId)->value('countdown_seconds') ?? 3600;

        // Hitung sisa detik: utamakan remaining_seconds tersimpan
        if ($enroll->remaining_seconds !== null) {
            $sisaDetik = max(0, (int) $enroll->remaining_seconds);
        } else {
            $sisaDetik = max(0, $countdownSeconds - ($enroll->elapsed_seconds ?? 0));
        }

        // Reset started_at ke sekarang (basis hitung elapsed baru), hapus paused_at.
        // remaining_seconds TIDAK dihapus di sini — heartbeat akan terus meng-update
        // nilainya setiap 10 detik sehingga selalu akurat saat finishTopic dipanggil.
        DB::table('select_student_topic_times')->where('id', $enroll->id)->update([
            'started_at'        => $now,
            'paused_at'         => null,
            'remaining_seconds' => $sisaDetik, // simpan nilai awal resume
            'updated_at'        => $now,
        ]);

        return response()->json(['success' => true, 'sisa_detik' => $sisaDetik]);
    }

    public function finishTopic(Request $request)
    {
        $userId  = Auth::id();
        $topicId = $request->input('mysqlid');
        $now     = now();

        $topicTime = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $topicId)->where('is_finished', 0)
            ->orderByDesc('id')->first();

        if ($topicTime) {
            $countdownSeconds = DB::table('select_topics')
                ->where('id', $topicId)->value('countdown_seconds') ?? 3600;
            if ($request->has('remaining_seconds')) {
                $sisaDetik = max(0, (int) $request->input('remaining_seconds'));
            } elseif ($topicTime->remaining_seconds !== null) {
                $sisaDetik = max(0, (int) $topicTime->remaining_seconds);
            } elseif ($topicTime->paused_at) {
                $sisaDetik = max(0, $countdownSeconds - ($topicTime->elapsed_seconds ?? 0));
            } else {
                $elapsedSejak = $now->diffInSeconds(\Carbon\Carbon::parse($topicTime->started_at));
                $totalElapsed = ($topicTime->elapsed_seconds ?? 0) + $elapsedSejak;
                $sisaDetik    = max(0, $countdownSeconds - $totalElapsed);
            }

            // Durasi = waktu yang benar-benar dipakai mengerjakan
            $duration = $countdownSeconds - $sisaDetik;

            DB::table('select_student_topic_times')->where('id', $topicTime->id)->update([
                'duration_seconds'  => $duration,
                'is_finished'       => 1,
                'remaining_seconds' => null,
                'updated_at'        => $now,
            ]);
            DB::table('select_user_reset')->updateOrInsert(
                ['user_id' => $userId, 'topic_id' => $topicId],
                ['is_reset' => true]
            );
        }

        return response()->json(['success' => true]);
    }

    public function resetTestingDatabase(Request $request)
    {
        $userId  = Auth::id();
        $mysqlid = $request->get('mysqlid');

        DB::table('select_user_reset')->updateOrInsert(
            ['user_id' => $userId, 'topic_id' => $mysqlid],
            ['is_reset' => true]
        );

        return response()->json(['success' => true, 'message' => 'Sesi berhasil direset!']);
    }

    // ------------------------------------------------------------------
    // Jalankan query dan kembalikan HTML tabel hasil
    // Dipakai saat showTopicDetail untuk pre-render hasil soal yang sudah benar
    // ------------------------------------------------------------------
    private function runQueryToHtml(string $query, int $topicId, int $userId): string
    {
        if (!preg_match('/^\s*select\s+/i', $query)) {
            return '';
        }
        try {
            $this->setupStudentTestingDatabase($userId, $topicId);
            $results = DB::connection('mysql_testing')->select($query);
            if (empty($results)) {
                return '<div style="color:red;padding:8px;">Data Not Found.</div>';
            }
            $columns = array_keys((array) $results[0]);
            $html = '<div style="overflow:auto;max-height:350px;"><table border="1" cellpadding="8" style="border-collapse:collapse;"><thead><tr>';
            foreach ($columns as $col) $html .= '<th style="background:#288cff;color:#fff;">' . htmlspecialchars($col) . '</th>';
            $html .= '</tr></thead><tbody>';
            foreach ($results as $row) {
                $html .= '<tr>';
                foreach ($columns as $col) $html .= '<td>' . htmlspecialchars($row->$col) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table></div>';
            return $html;
        } catch (\Exception $e) {
            return '';
        }
    }

    // ------------------------------------------------------------------
    // FITUR HINT
    //
    // Dipanggil via AJAX saat student klik tombol "Lihat Hint" setelah
    // gagal lebih dari 2 kali (attempts > 2) pada soal TUGAS.
    //
    // Tanpa tabel baru — memanfaatkan select_student_submissions yang
    // sudah ada. Tiap klik hint menambah 2 baris submission bertanda
    // khusus (query = '[HINT_PENALTY]', status = 'false'). Baris ini
    // ikut dihitung sebagai attempt oleh calcQuestionScore() yang sudah
    // ada, sehingga otomatis memotong 2 x 10 = 20 poin tanpa perlu
    // mengubah rumus skor sama sekali.
    //
    // Baris '[HINT_PENALTY]' juga dipakai sebagai penanda "hint sudah
    // pernah dibuka" untuk soal ini, supaya tombol hint di-disable dan
    // tidak bisa dipakai berkali-kali (lihat showTopicDetail()).
    // ------------------------------------------------------------------
    public function showHint(Request $request)
    {
        $request->validate([
            'topic_detail_id' => 'required|integer',
            'mysqlid'         => 'required|integer',
            'submission_type' => 'required|in:tugas', // hint hanya untuk tugas
            'answer_number'   => 'required|integer|min:1',
        ]);

        $userId         = Auth::id();
        $topicDetailId  = $request->input('topic_detail_id');
        $mysqlid        = $request->input('mysqlid');
        $submissionType = 'tugas';
        $answerNumber   = $request->input('answer_number');

        // Cari sesi aktif student
        $enroll = DB::table('select_student_topic_times')
            ->where('user_id', $userId)->where('topic_id', $mysqlid)->where('is_finished', 0)
            ->orderByDesc('id')->first();

        if (!$enroll) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan.'], 422);
        }
        $enrollId = $enroll->id;

        // Soal sudah pernah benar → tidak boleh hint lagi
        $alreadyCorrect = DB::table('select_student_submissions')
            ->where('user_id', $userId)->where('topic_detail_id', $topicDetailId)
            ->where('submission_type', $submissionType)->where('answer_number', $answerNumber)
            ->where('enroll_id', $enrollId)->where('status', 'true')->exists();

        if ($alreadyCorrect) {
            return response()->json(['success' => false, 'message' => 'Soal ini sudah dijawab benar.'], 422);
        }

        // Hint sudah pernah dibuka untuk soal ini → tidak boleh dibuka lagi
        $hintAlreadyUsed = DB::table('select_student_submissions')
            ->join('select_queries', 'select_queries.id', '=', 'select_student_submissions.query_id')
            ->where('select_student_submissions.user_id', $userId)
            ->where('select_student_submissions.topic_detail_id', $topicDetailId)
            ->where('select_student_submissions.submission_type', $submissionType)
            ->where('select_student_submissions.answer_number', $answerNumber)
            ->where('select_student_submissions.enroll_id', $enrollId)
            ->where('select_queries.query', '[HINT_PENALTY]')
            ->exists();

        if ($hintAlreadyUsed) {
            return response()->json(['success' => false, 'message' => 'Hint untuk soal ini sudah pernah dibuka.'], 422);
        }

        // Attempts asli (tidak termasuk baris penalti hint) harus > 2
        $attempts = DB::table('select_student_submissions')
            ->join('select_queries', 'select_queries.id', '=', 'select_student_submissions.query_id')
            ->where('select_student_submissions.user_id', $userId)
            ->where('select_student_submissions.topic_detail_id', $topicDetailId)
            ->where('select_student_submissions.submission_type', $submissionType)
            ->where('select_student_submissions.answer_number', $answerNumber)
            ->where('select_student_submissions.enroll_id', $enrollId)
            ->where('select_queries.query', '!=', '[HINT_PENALTY]')
            ->count();

        if ($attempts <= 2) {
            return response()->json(['success' => false, 'message' => 'Hint hanya tersedia setelah mencoba lebih dari 2 kali.'], 422);
        }

        // Ambil kunci jawaban
        $expected = SelectExpectedQuery::where('topic_detail_id', $topicDetailId)
            ->where('type', $submissionType)
            ->where('answer_number', $answerNumber)
            ->first();

        if (!$expected || empty($expected->expected_query)) {
            return response()->json(['success' => false, 'message' => 'Kunci jawaban tidak tersedia untuk soal ini.'], 422);
        }

        // Catat penalti: 2 baris submission gagal bertanda '[HINT_PENALTY]'
        // → attempts naik 2 → calcQuestionScore() otomatis memotong 20 poin
        for ($i = 0; $i < 2; $i++) {
            $queryId = DB::table('select_queries')->insertGetId([
                'query'      => '[HINT_PENALTY]',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('select_student_submissions')->insert([
                'user_id'         => $userId,
                'enroll_id'       => $enrollId,
                'topic_detail_id' => $topicDetailId,
                'submission_type' => $submissionType,
                'answer_number'   => $answerNumber,
                'query_id'        => $queryId,
                'feedback_id'     => null,
                'status'          => 'false',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        Log::info("Hint dibuka oleh user {$userId} untuk topic_detail_id {$topicDetailId}, soal tugas #{$answerNumber} (-20 poin)");

        return response()->json([
            'success' => true,
            'hint'    => $expected->expected_query,
            'message' => 'Hint berhasil dibuka. Skor soal ini dikurangi 20 poin.',
        ]);
    }

    // public function runUserSelectQuery(Request $request)
    // {
    //     $userId  = Auth::id();
    //     $mysqlid = $request->get('mysqlid');
    //     $this->setupStudentTestingDatabase($userId, $mysqlid);

    //     $request->validate(['userSelectQuery' => 'required|string|max:5000']);
    //     $query = trim($request->input('userSelectQuery'));

    //     if (!preg_match('/^\s*select\s+/i', $query)) {
    //         $html = '<div style="color:red;padding:8px;">Only SELECT queries are allowed here!</div>';
    //         return $request->ajax() ? response()->json(['html' => $html]) : back()->with('query_result', $html);
    //     }

    //     try {
    //         $results = DB::connection('mysql_testing')->select($query);
    //         if (empty($results)) {
    //             $html = '<div style="color:red;padding:8px;">Data Not Found.</div>';
    //         } else {
    //             $columns = array_keys((array) $results[0]);
    //             $html = '<div style="overflow:auto;max-height:350px;"><table border="1" cellpadding="8" style="border-collapse:collapse;"><thead><tr>';
    //             foreach ($columns as $col) $html .= '<th style="background:#288cff;color:#fff;">' . htmlspecialchars($col) . '</th>';
    //             $html .= '</tr></thead><tbody>';
    //             foreach ($results as $row) {
    //                 $html .= '<tr>';
    //                 foreach ($columns as $col) $html .= '<td>' . htmlspecialchars($row->$col) . '</td>';
    //                 $html .= '</tr>';
    //             }
    //             $html .= '</tbody></table></div>';
    //         }
    //     } catch (\Exception $e) {
    //         $html = '<div style="color:red;padding:8px;">' . htmlspecialchars($e->getMessage()) . '</div>';
    //     }

    //     return $request->ajax() ? response()->json(['html' => $html]) : back()->with('query_result', $html);
    // }
}
