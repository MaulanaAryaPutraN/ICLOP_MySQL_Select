<?php

namespace App\Http\Controllers\MySQL;

use App\Http\Controllers\Controller;
use App\Models\MySQL\MySqlTopicDetails;
use App\Models\MySQL\MySqlTopics;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MysqlStudentController extends Controller
{
    public function showTopicDetail(Request $request)
    {
        $mysqlid = (int) $request->get('mysqlid');
        $start = (int) $request->get('start');
        $output = $request->get('output', '');
        $answerStatus = session('answer_status');
        $userId = Auth::user()->id;

        $detail = MySqlTopicDetails::findOrFail($start);
        $page = (int) $request->get('page', 1);
        $totalAnswer = $detail->total_question;

        $topics = MySqlTopics::all()->map(function ($topic) {
            $topic->has_schema = $topic->schema_file_name && $topic->schema_file_path ? true : false;
            return $topic;
        });
        $topicsNavbar = MySqlTopics::findOrFail($mysqlid);
        $countdownSeconds = $topicsNavbar->countdown_seconds ?? 3600;
        $topic = MySqlTopics::find($request->input('mysqlid'));
        $isSequential = $topic && $topic->is_sequential ? true : false;

        // Ambil semua subtopik pada topik ini, urutkan
        $subtopics = MySqlTopicDetails::where('topic_id', $mysqlid)->orderBy('id')->get();
        $globalQuestions = [];
        foreach ($subtopics as $subtopic) {
            for ($i = 1; $i <= $subtopic->total_question; $i++) {
                $globalQuestions[] = [
                    'topic_detail_id' => $subtopic->id,
                    'answer_number' => $i,
                ];
            }
        }
        // Cari index soal saat ini di urutan global
        $currentIndex = collect($globalQuestions)->search(function ($q) use ($detail, $page) {
            return $q['topic_detail_id'] == $detail->id && $q['answer_number'] == $page;
        });

        // 1. Ambil sesi enroll aktif
        $topicTime = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $mysqlid)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();

        $isFinished = false;
        $sisaDetik = $countdownSeconds;

        // 2. Jika ada sesi aktif, cek waktu habis
        if ($topicTime && $topicTime->started_at) {
            $elapsed = now()->diffInSeconds(\Carbon\Carbon::parse($topicTime->started_at));
            $sisaDetik = max(0, $countdownSeconds - $elapsed);

            if ($sisaDetik <= 0 && $topicTime->is_finished == 0) {
                // Tandai sesi selesai
                DB::table('mysql_student_topic_times')
                    ->where('id', $topicTime->id)
                    ->update([
                        'duration_seconds' => $countdownSeconds,
                        'is_finished' => 1,
                        'updated_at' => now()
                    ]);
                DB::statement("DROP DATABASE IF EXISTS iclop_user_$userId");
                DB::table('mysql_user_reset')->updateOrInsert(
                    ['user_id' => $userId, 'topic_id' => $mysqlid],
                    ['is_reset' => true]
                );
                // Buat sesi baru
                $this->setupStudentTestingDatabase($userId);
                $now = now();
                DB::table('mysql_student_topic_times')->insert([
                    'user_id' => $userId,
                    'topic_id' => $mysqlid,
                    'started_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'is_finished' => 0,
                ]);
                DB::table('mysql_user_reset')->updateOrInsert(
                    ['user_id' => $userId, 'topic_id' => $mysqlid],
                    ['is_reset' => 0]
                );
                $sisaDetik = $countdownSeconds;
            }
        }

        // 3. Jika tidak ada sesi aktif, buat sesi baru
        $activeEnroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $mysqlid)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();
        if (!$activeEnroll) {
            $this->setupStudentTestingDatabase($userId);
            $now = now();
            DB::table('mysql_student_topic_times')->insert([
                'user_id' => $userId,
                'topic_id' => $mysqlid,
                'started_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
                'is_finished' => 0,
            ]);
            DB::table('mysql_user_reset')->updateOrInsert(
                ['user_id' => $userId, 'topic_id' => $mysqlid],
                ['is_reset' => 0]
            );
            $sisaDetik = $countdownSeconds;
        }

        // 4. Ambil ulang enroll aktif terbaru
        $enroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $mysqlid)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();
        $enrollId = $enroll ? $enroll->id : null;

        // --- Pindahkan logika ini ke sini ---
        $canAnswer = true;
        if ($isSequential && $currentIndex > 0) {
            $prevQ = $globalQuestions[$currentIndex - 1];
            $prevSubmission = DB::table('mysql_student_submissions')
                ->where('user_id', $userId)
                ->where('topic_detail_id', $prevQ['topic_detail_id'])
                ->where('answer_number', $prevQ['answer_number'])
                ->where('enroll_id', $enrollId)
                ->where('status', 'true')
                ->first();
            $canAnswer = $prevSubmission ? true : false;
        }

        // 5. Ambil progress dan jawaban dari enrollId terbaru
        $progressPercent = $this->getStudentProgressByEnroll($userId, $mysqlid, $enrollId);

        $submission = DB::table('mysql_student_submissions')
            ->select('id', 'query_id', 'status', 'feedback_id') // Select hanya kolom yang diperlukan
            ->where('user_id', $userId)
            ->where('topic_detail_id', $start)
            ->where('answer_number', $page)
            ->where('enroll_id', $enrollId)
            ->orderByDesc('id')
            ->limit(1) // Tambahkan limit
            ->first();

        $lastAnswer = '';
        $lastStatus = null;
        $lastSubmission = $submission;
        if ($submission) {
            $lastQuery = DB::table('mysql_queries')->where('id', $submission->query_id)->first();
            $lastAnswer = $lastQuery ? $lastQuery->query : '';
            $lastStatus = $submission->status ?? null;
        }

        $results = DB::select("select * from mysql_topic_details where topic_id = $mysqlid and id ='$start' ");
        $rows = DB::table('mysql_topic_details')->where('topic_id', $mysqlid)->get();
        $html_start = '';
        $pdf_reader = 0;
        foreach ($results as $r) {
            if ($mysqlid == $r->topic_id) {
                if (!empty($r->file_name)) {
                    $html_start = $r->file_name;
                    $pdf_reader = 1;
                } else {
                    $html_start = "No Modules";
                    $pdf_reader = 0;
                }
                break;
            }
        }
        $idUser = Auth::user()->id;
        $roleTeacher = DB::select("select role from users where id = $idUser");
        $topicsCount = count($topics);
        $detailCount = ($topicsCount / $topicsCount) * 10;

        if ($request->ajax()) {
            return view('mysql_dml.student.material._answer_section', compact(
                'detail',
                'topics',
                'topicsNavbar',
                'mysqlid',
                'html_start',
                'pdf_reader',
                'topicsCount',
                'detailCount',
                'output',
                'roleTeacher',
                'answerStatus',
                'lastAnswer',
                'lastStatus',
                'lastSubmission',
                'progressPercent',
                'totalAnswer',
                'page',
                'enrollId',
                'canAnswer',
                'isSequential'
            ));
        }

        return view('mysql_dml.student.material.topic_detail', [
            'row' => $detail,
            'topics' => $topics,
            'topicsNavbar' => $topicsNavbar,
            'mysqlid' => $mysqlid,
            'html_start' => $html_start,
            'pdf_reader' => $pdf_reader,
            'topicsCount' => $topicsCount,
            'detailCount' => $detailCount,
            'output' => $output,
            'role' => isset($roleTeacher[0]) ? $roleTeacher[0]->role : '',
            'answerStatus' => $answerStatus,
            'lastAnswer' => $lastAnswer,
            'lastStatus' => $lastStatus,
            'lastSubmission' => $lastSubmission,
            'detail' => $detail,
            'progressPercent' => $progressPercent,
            'totalAnswer' => $totalAnswer,
            'page' => $page,
            'rows' => $rows,
            'countdownSeconds' => $sisaDetik,
            'isFinished' => $isFinished,
            'enrollId' => $enrollId,
            'isSequential' => $isSequential,
            'canAnswer' => $canAnswer,
        ]);
    }

    public function getStudentProgressByEnroll($userId, $topicId, $enrollId)
    {
        // Ambil semua id subtopik pada topik ini
        $subtopicIds = DB::table('mysql_topic_details')
            ->where('topic_id', $topicId)
            ->pluck('id');

        // Hitung total expected answer (total_question) pada semua subtopik
        $totalAnswer = DB::table('mysql_topic_details')
            ->where('topic_id', $topicId)
            ->sum('total_question');

        // Hitung jumlah submission status=true pada semua subtopik topik ini dan enroll aktif
        $correctSubmissions = DB::table('mysql_student_submissions')
            ->where('user_id', $userId)
            ->where('status', 'true')
            ->whereIn('topic_detail_id', $subtopicIds)
            ->where('enroll_id', $enrollId)
            ->count();

        // Hitung persentase progress
        $progressPercent = $totalAnswer > 0 ? round(($correctSubmissions / $totalAnswer) * 100) : 0;

        return $progressPercent;
    }

    public function enrollTopic(Request $request)
    {
        $userId = Auth::id();
        $topicId = $request->input('mysqlid');
        $now = now();

        // Cek sesi enroll aktif (belum selesai)
        $activeEnroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();

        // Cek status reset
        $userReset = DB::table('mysql_user_reset')
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->value('is_reset');

        if ($activeEnroll && !$userReset) {
            // Sesi aktif, tidak perlu create database/enroll baru
            return response()->json(['success' => true, 'enroll_id' => $activeEnroll->id]);
        } else {
            // Sesi tidak aktif, buat database testing baru & enroll baru
            $this->setupStudentTestingDatabase($userId);

            $enrollId = DB::table('mysql_student_topic_times')->insertGetId([
                'user_id' => $userId,
                'topic_id' => $topicId,
                'started_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
                'is_finished' => 0,
            ]);

            // Reset status user_reset
            DB::table('mysql_user_reset')->updateOrInsert(
                ['user_id' => $userId, 'topic_id' => $topicId],
                ['is_reset' => 0]
            );

            return response()->json(['success' => true, 'enroll_id' => $enrollId]);
        }
    }

    private function setupStudentTestingDatabase($userId)
    {
        $dbName = "iclop_user_" . $userId;
        $dbUser = env('DB_TESTING_USERNAME', 'root');
        $dbPass = env('DB_TESTING_PASSWORD', '');
        $dbHost = env('DB_TESTING_HOST', '127.0.0.1');
        $dbPort = env('DB_TESTING_PORT', '3306');
        $topicId = request()->input('mysqlid');
        $topic = MySqlTopics::find($topicId);

        // Hanya gunakan file schema dari dosen (yang sudah diupload)
        if ($topic && $topic->schema_file_name && $topic->schema_file_path) {
            // Jika path diawali 'public/' atau 'mysql/schema/', gunakan public_path
            if (
                str_starts_with($topic->schema_file_path, 'public/') ||
                str_starts_with($topic->schema_file_path, 'mysql/schema/')
            ) {
                $relativePath = ltrim(str_replace('public/', '', $topic->schema_file_path), '/\\');
                $templatePath = public_path($relativePath . $topic->schema_file_name);
            } else {
                // Jika path tidak valid, tolak
                throw new Exception('Invalid schema file path for this topic.');
            }
        } else {
            // Tidak ada schema, hentikan proses atau lempar error
            throw new Exception('Schema database untuk topik ini belum tersedia.');
        }

        // 1. Buat database jika belum ada
        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // 2. Import template SQL jika tabel utama belum ada
        $tables = DB::select("SHOW TABLES FROM `$dbName`");
        if (count($tables) == 0) {
            $importCmd = "mysql -h $dbHost -P $dbPort -u $dbUser " . ($dbPass ? "-p\"$dbPass\"" : "") . " $dbName < \"$templatePath\"";
            shell_exec($importCmd);
        }

        // 3. Set koneksi dinamis ke database ini
        config(['database.connections.mysql_testing.database' => $dbName]);
        DB::purge('mysql_testing');
        DB::reconnect('mysql_testing');
    }

    public function submitUserInput(Request $request)
    {
        $request->validate([
            'userInput' => 'required|string|max:255',
            'topic_detail_id' => 'required|integer',
            'mysqlid' => 'required|integer',
            'start' => 'required|integer',
        ]);

        $userInput = trim($request->input('userInput'));
        $topicDetailId = $request->input('topic_detail_id');
        $userId = Auth::user()->id;
        $answerNumber = $request->input('answer_number', 1);

        // Tambahkan ini:
        $topic = MySqlTopics::find($request->input('mysqlid'));
        $isSequential = $topic && $topic->is_sequential ? true : false;

        // Cek jika query SELECT, langsung salah
        if (preg_match('/^\s*select\s+/i', $userInput)) {
            $shortFeedback = $this->addDefaultMessage('', false);
            // Simpan query ke mysql_queries
            $queryId = DB::table('mysql_queries')->insertGetId([
                'query' => $userInput,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Simpan feedback ke mysql_feedbacks
            $feedbackId = DB::table('mysql_feedbacks')->insertGetId([
                'query_id' => $queryId,
                'feedback' => $shortFeedback,
                'validation_error' => $shortFeedback,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Ambil enroll aktif
            $enroll = DB::table('mysql_student_topic_times')
                ->where('user_id', $userId)
                ->where('topic_id', $request->input('mysqlid'))
                ->where('is_finished', 0)
                ->orderByDesc('id')
                ->first();
            $enrollId = $enroll ? $enroll->id : null;
            // Simpan ke mysql_student_submissions
            DB::table('mysql_student_submissions')->insert([
                'user_id' => $userId,
                'enroll_id' => $enrollId,
                'topic_detail_id' => $topicDetailId,
                'query_id' => $queryId,
                'feedback_id' => $feedbackId,
                'status' => 'false',
                'answer_number' => $answerNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->route('showTopicDetail', [
                'mysqlid' => $request->input('mysqlid'),
                'start' => $request->input('start'),
                'page' => $request->input('answer_number', 1)
            ])->with('answer_status', $shortFeedback);
        }

        // Panggil setup database user sebelum transaksi
        $this->setupStudentTestingDatabase($userId);

        // Ambil enroll aktif
        $enroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $request->input('mysqlid'))
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();
        $enrollId = $enroll ? $enroll->id : null;

        // 1. Simpan query ke mysql_queries
        $queryId = DB::table('mysql_queries')->insertGetId([
            'query' => $userInput,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Jalankan Codeception (pastikan query user bisa diakses oleh UserQueryCest)
        // Kirim query_id sebagai environment variable
        $acceptanceConfig = file_get_contents(base_path('tests/Acceptance.suite.yml'));
        $acceptanceConfig = str_replace(
            "dbname=iclop_v2_testing",
            "dbname=iclop_user_{$userId}",
            $acceptanceConfig
        );

        // Tambahkan blok namespace dan support_namespace di bagian atas jika belum ada
        if (strpos($acceptanceConfig, 'namespace: Tests') === false) {
            $acceptanceConfig = "namespace: Tests\nsupport_namespace: Support\n\n" . $acceptanceConfig;
        }

        // Tambahkan blok paths absolut jika belum ada
        $pathsBlock = <<<YML
        paths:
            tests: .
            output: _output
            data: Support/Data
            support: Support
            envs: _envs

        YML;

        if (strpos($acceptanceConfig, 'paths:') === false) {
            $acceptanceConfig .= "\n" . $pathsBlock;
        }

        file_put_contents(base_path("tests/acceptance_user_{$userId}.suite.yml"), $acceptanceConfig);

        $projectPath = base_path();
        $command = "cd /d \"{$projectPath}\\tests\" && set USER_ID={$userId} && set QUERY_ID={$queryId} && set USER_QUERY=" . escapeshellarg($userInput) . " && \"{$projectPath}\\vendor\\bin\\codecept.bat\" run acceptance_user_{$userId} acceptance/UserQueryCest:testUserQuery -c acceptance_user_{$userId}.suite.yml --env testing 2>&1";

        Log::info("Codeception command: " . $command);
        $testResult = shell_exec($command);

        Log::info("Codeception output: " . $testResult);

        // Debug: log atau tampilkan hasil
        if ($testResult === null || trim($testResult) === '') {
            return back()->with('answer_status', "Codeception tidak berjalan. Cek perintah: $command");
        }

        Log::info("Codeception output: " . $testResult);

        // 3. Simpan feedback ke mysql_feedbacks
        $validationError = null;
        $shortFeedback = $this->getShortFeedback($testResult);
        $feedbackId = DB::table('mysql_feedbacks')->insertGetId([
            'query_id' => $queryId,
            'feedback' => $shortFeedback,
            'validation_error' => $validationError,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Simpan ke mysql_student_submissions
        $status = 'false';
        if (
            strpos($testResult, 'OK (1 test') !== false &&
            stripos($testResult, 'error') === false &&
            stripos($testResult, 'Exception') === false &&
            stripos($testResult, 'SQLSTATE') === false // tambahkan pengecekan SQLSTATE
        ) {
            $status = 'true';
        }

        // Ambil expected query dulu
        $expected = DB::table('mysql_expected_queries')
            ->where('topic_detail_id', $topicDetailId)
            ->where('answer_number', $answerNumber)
            ->first();

        $userIsCreateTable = stripos($userInput, 'CREATE TABLE') !== false;
        $expectedIsCreateTable = $expected && stripos($expected->expected_query, 'CREATE TABLE') !== false;

        // Hanya untuk CREATE TABLE yang diberi validasi khusus
        if ($userIsCreateTable && $expectedIsCreateTable) {
            // Validasi CREATE TABLE dengan pesan generic jika salah
            if (stripos($userInput, 'CREATE TABLE') !== false) {
                $expected = DB::table('mysql_expected_queries')
                    ->where('topic_detail_id', $topicDetailId)
                    ->where('answer_number', $answerNumber)
                    ->first();

                // Cek apakah kunci jawaban juga menggunakan CREATE TABLE
                if (isset($expected) && stripos($expected->expected_query, 'CREATE TABLE') !== false) {
                    $expectedTable = $expected->expected_table ?? null;
                    if ($expectedTable) {
                        $userTableNorm = [];
                        $expectedTableNorm = [];
                        $userTableName = '';
                        $expectedTableName = '';

                        // 1. Jalankan query user (CREATE TABLE)
                        try {
                            DB::connection('mysql_testing')->statement($userInput);

                            // 2. Ambil nama tabel dari query user
                            if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([a-zA-Z_][a-zA-Z0-9_]*)`?/i', $userInput, $matches)) {
                                $userTableName = $matches[1];
                            }

                            // Ambil struktur tabel buatan user
                            $userTable = DB::connection('mysql_testing')->select("DESCRIBE `$userTableName`");

                            // 3. Drop tabel buatan user
                            DB::connection('mysql_testing')->statement("DROP TABLE IF EXISTS `$userTableName`");
                        } catch (\Exception $e) {
                            $status = 'false';
                            $validationError = $this->addDefaultMessage($e->getMessage(), false);
                            // Pastikan tabel dihapus jika error
                            if ($userTableName) {
                                try {
                                    DB::connection('mysql_testing')->statement("DROP TABLE IF EXISTS `$userTableName`");
                                } catch (\Exception $ex) {
                                }
                            }
                        }

                        // 4. Jalankan query dari kunci jawaban (CREATE TABLE)
                        try {
                            DB::connection('mysql_testing')->statement($expected->expected_query);

                            // Ambil nama tabel dari query kunci jawaban
                            if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([a-zA-Z_][a-zA-Z0-9_]*)`?/i', $expected->expected_query, $matches)) {
                                $expectedTableName = $matches[1];
                            }

                            // 5. Ambil struktur tabel dari kunci jawaban
                            $expectedTableStruct = DB::connection('mysql_testing')->select("DESCRIBE `$expectedTableName`");

                            // 6. Drop tabel dari kunci jawaban
                            DB::connection('mysql_testing')->statement("DROP TABLE IF EXISTS `$expectedTableName`");
                        } catch (\Exception $e) {
                            // Handle jika kunci jawaban error
                            if ($expectedTableName) {
                                try {
                                    DB::connection('mysql_testing')->statement("DROP TABLE IF EXISTS `$expectedTableName`");
                                } catch (\Exception $ex) {
                                }
                            }
                        }

                        // 7. Bandingkan nama tabel
                        if (strtolower($userTableName) !== strtolower($expectedTableName)) {
                            $status = 'false';
                            $validationError = $this->addDefaultMessage('The created table is incorrect or does not match the expected answer', false);
                        } else {
                            // 8. Normalisasi dan bandingkan struktur tabel
                            $normalizeTable = function ($table) {
                                return array_map(function ($col) {
                                    return [
                                        'Field' => strtolower($col->Field),
                                        'Type' => strtolower($col->Type),
                                        'Null' => strtolower($col->Null),
                                        'Key' => strtolower($col->Key ?? ''),
                                        'Default' => $col->Default,
                                        'Extra' => strtolower($col->Extra ?? '')
                                    ];
                                }, $table);
                            };

                            $userTableNorm = $normalizeTable($userTable ?? []);
                            $expectedTableNorm = $normalizeTable($expectedTableStruct ?? []);

                            // Bandingkan struktur tabel
                            if ($userTableNorm !== $expectedTableNorm) {
                                $status = 'false';
                                $validationError = $this->addDefaultMessage('The created table is incorrect or does not match the expected answer', false);
                            } else {
                                // 9. Jika nama dan struktur sama, create ulang tabel user
                                try {
                                    DB::connection('mysql_testing')->statement($userInput);
                                    $status = 'true';
                                    $validationError = $this->addDefaultMessage('', true);
                                } catch (\Exception $e) {
                                    $status = 'false';
                                    $validationError = $this->addDefaultMessage($e->getMessage(), false);
                                }
                            }
                        }
                    }
                } else {
                    // Jika kunci jawaban bukan CREATE TABLE, tidak izinkan CREATE TABLE
                    $status = 'false';
                    $validationError = $this->addDefaultMessage('CREATE TABLE queries are not allowed for this question!', false);
                }
            }
        } else if ($userIsCreateTable && !$expectedIsCreateTable) {
            // User pakai CREATE TABLE tapi expected bukan CREATE TABLE
            $status = 'false';
            $validationError = $this->addDefaultMessage('', false);
        } else if (!$userIsCreateTable && $expectedIsCreateTable) {
            // Expected CREATE TABLE tapi user tidak pakai CREATE TABLE
            $status = 'false';
            $validationError = $this->addDefaultMessage('', false);
        } else if ($status === 'true') {
            // ✅ Untuk soal NON-CREATE TABLE, langsung ke validasi normal
            // TIDAK ADA pesan generic di sini
            if ($expected) {
                try {
                    // --- 1. Jalankan query user dalam transaksi, ambil hasil, rollback ---
                    DB::connection('mysql_testing')->beginTransaction();
                    try {
                        DB::connection('mysql_testing')->statement($userInput);
                        $studentResult = DB::connection('mysql_testing')->select("SELECT * FROM {$expected->expected_table}");
                        DB::connection('mysql_testing')->rollBack();
                        $validationError = null;
                    } catch (\Exception $e) {
                        DB::connection('mysql_testing')->rollBack();
                        $status = 'false';
                        $validationError = $this->addDefaultMessage($e->getMessage(), false);
                    }

                    // Jika terjadi error pada eksekusi query user, langsung simpan feedback dan return
                    if ($status === 'false') {
                        // Update feedback dengan validation error
                        DB::table('mysql_feedbacks')->where('id', $feedbackId)->update([
                            'validation_error' => $validationError,
                            'updated_at' => now(),
                        ]);

                        // Simpan ke mysql_student_submissions
                        DB::table('mysql_student_submissions')->insert([
                            'user_id' => $userId,
                            'enroll_id' => $enrollId,
                            'topic_detail_id' => $topicDetailId,
                            'query_id' => $queryId,
                            'feedback_id' => $feedbackId,
                            'status' => $status,
                            'answer_number' => $answerNumber,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        return redirect()->route('showTopicDetail', [
                            'mysqlid' => $request->input('mysqlid'),
                            'start' => $request->input('start'),
                            'page' => $request->input('answer_number', 1)
                        ])->with('answer_status', $shortFeedback);
                    }

                    // --- 2. Jalankan query expected dalam transaksi, ambil hasil, rollback ---
                    DB::connection('mysql_testing')->beginTransaction();
                    DB::connection('mysql_testing')->statement($expected->expected_query);
                    $expectedResult = DB::connection('mysql_testing')->select("SELECT * FROM {$expected->expected_table}");
                    DB::connection('mysql_testing')->rollBack();

                    // --- 3. Normalisasi hasil ---
                    function normalizeResult($result)
                    {
                        $arr = array_map(function ($row) {
                            return (array) $row;
                        }, $result);
                        usort($arr, function ($a, $b) {
                            return strcmp($a['kode_mk'], $b['kode_mk']);
                        });
                        return $arr;
                    }
                    $studentResultNorm = normalizeResult($studentResult);
                    $expectedResultNorm = normalizeResult($expectedResult);

                    // --- Tambahan validasi logika query (misal: wajib WHERE pada soal DML) ---
                    $expectedQuery = strtolower($expected->expected_query ?? '');
                    $userQuery = strtolower($userInput);

                    // Jika expected query mengandung 'delete' atau 'update' dan ada 'where', user juga harus pakai WHERE
                    if (
                        (strpos($expectedQuery, 'delete') !== false || strpos($expectedQuery, 'update') !== false)
                        && strpos($expectedQuery, 'where') !== false
                    ) {
                        if (strpos($userQuery, 'where') === false) {
                            $status = 'false';
                            $validationError = $this->addDefaultMessage('Your query must use WHERE clause according to the question instructions!', false);
                        }
                    }

                    // Jika expected query adalah DELETE tanpa WHERE, user juga tidak boleh pakai WHERE
                    if (
                        strpos($expectedQuery, 'delete') !== false &&
                        strpos($expectedQuery, 'where') === false
                    ) {
                        if (strpos($userQuery, 'where') !== false) {
                            $status = 'false';
                            $validationError = $this->addDefaultMessage('Your query should not use WHERE clause according to the question instructions!', false);
                        }
                    }

                    // --- 4. Bandingkan hasil ---
                    if ($status === 'true' && $studentResultNorm == $expectedResultNorm) {
                        // --- 5. Jalankan query user sekali lagi (commit/rollback sesuai sequential) ---
                        DB::connection('mysql_testing')->beginTransaction();
                        try {
                            DB::connection('mysql_testing')->statement($userInput);
                            if ($isSequential) {
                                DB::connection('mysql_testing')->commit(); // Sequential: commit perubahan
                            } else {
                                DB::connection('mysql_testing')->rollBack(); // Non-sequential: rollback, jangan commit
                            }
                            $status = 'true';
                            $validationError = $this->addDefaultMessage('', true);
                        } catch (\Exception $e) {
                            DB::connection('mysql_testing')->rollBack();
                            $status = 'false';
                            $validationError = $this->addDefaultMessage($e->getMessage(), false);
                        }
                    } else if ($status === 'true') {
                        $status = 'false';
                        $validationError = $this->addDefaultMessage('', false);
                    }
                } catch (\Exception $e) {
                    DB::connection('mysql_testing')->rollBack();
                    $status = 'false';
                    $validationError = $this->addDefaultMessage($e->getMessage(), false);
                }
            }
        }

        // Update feedback dengan validation error atau success message jika ada
        if ($validationError) {
            DB::table('mysql_feedbacks')->where('id', $feedbackId)->update([
                'validation_error' => $validationError,
                'updated_at' => now(),
            ]);
        }

        DB::table('mysql_student_submissions')->insert([
            'user_id' => $userId,
            'enroll_id' => $enrollId, // tambahkan ini!
            'topic_detail_id' => $topicDetailId,
            'query_id' => $queryId,
            'feedback_id' => $feedbackId,
            'status' => $status,
            'answer_number' => $answerNumber, // tambahkan ini
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('showTopicDetail', [
            'mysqlid' => $request->input('mysqlid'),
            'start' => $request->input('start'),
            'page' => $request->input('answer_number', 1)
        ])->with('answer_status', $shortFeedback);
    }

    private function getShortFeedback($testResult)
    {
        // Hilangkan karakter escape ANSI (warna terminal dsb)
        $testResult = preg_replace('/\e\[[\d;]*m/', '', $testResult);

        // Jika benar, tampilkan hanya "OK (1 test)"
        if (strpos($testResult, 'OK (1 test') !== false) {
            return 'OK (1 test)';
        }

        // Jika salah, ambil error tanpa "Assertions"
        $lines = explode("\n", $testResult);
        $feedbackLines = [];
        foreach ($lines as $line) {
            $line = trim($line);
            // Hilangkan baris yang mengandung "Assertions"
            if (stripos($line, 'Assertions') !== false) {
                continue;
            }
            // Ambil baris error penting
            if (
                stripos($line, 'error') !== false ||
                stripos($line, 'Exception') !== false ||
                stripos($line, 'SQLSTATE') !== false ||
                stripos($line, 'syntax error') !== false
            ) {
                $feedbackLines[] = $line;
            }
        }
        // Jika tidak ada error khusus, tampilkan baris non-kosong selain assertions
        if (empty($feedbackLines)) {
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '' && stripos($line, 'Assertions') === false) {
                    $feedbackLines[] = $line;
                }
            }
        }
        return implode('<br>', $feedbackLines);
    }

    public function runUserSelectQuery(Request $request)
    {
        $userId = Auth::user()->id;
        $this->setupStudentTestingDatabase($userId);

        $request->validate([
            'userSelectQuery' => 'required|string|max:255',
        ]);
        $query = trim($request->input('userSelectQuery'));

        // Hanya izinkan SELECT
        if (!preg_match('/^select\s+/i', $query)) {
            $html = '<div style="
                background-color: #f8d7da;
                color: #ff0000;
                border-radius: 0.5rem;
                display: inline-block;
                font-size: 14px;
                padding: 8px 10px;
                margin-bottom: 16px;
            ">Only <b>SELECT</b> queries are allowed!</div>';
            if ($request->ajax()) {
                return response()->json(['html' => $html]);
            }
            return back()->with('query_result', $html)->withInput();
        }

        try {
            $results = DB::connection('mysql_testing')->select($query);

            if (empty($results)) {
                $html = '<div style="
                    background-color: #f8d7da;
                    color: #ff0000;
                    border-radius: 0.5rem;
                    display: inline-block;
                    font-size: 14px;
                    padding: 8px 10px;
                    margin-bottom: 16px;
                ">Data Not found.</div>';
            } else {
                $columns = array_keys((array)$results[0]);
                $html = <<<HTML
                <style>
                    .iclop-table-custom thead th {
                        background: #288cff !important;
                        color: #ffffff !important;
                        font-weight: bold !important;
                        text-align: center !important;
                        white-space: nowrap;
                    }
                    .iclop-table-custom tbody tr:hover td {
                        background: #e3f0fb !important;
                        transition: background 0.2s;
                    }
                    .iclop-table-custom td, .iclop-table-custom th {
                        border: 1px solid #288cff !important;
                        white-space: nowrap;
                    }
                    .iclop-table-scroll {
                        max-height: 350px;
                        overflow-y: auto;
                        overflow-x: auto;
                        margin: 20px auto;
                        display: flex;
                        justify-content: center;
                        width: fit-content;
                        max-width: 100%;
                    }
                </style>
                <div class="iclop-table-scroll">
                <table class="iclop-table-custom" style="
                    width: auto;
                    min-width: 100px;
                    border-spacing:0;
                    border-radius:5px;
                    overflow:hidden;
                    background:#fff;
                    margin: 0 auto;
                ">
                    <thead>
                        <tr>
                HTML;
                foreach ($columns as $col) {
                    $html .= '<th style="padding:8px 14px;">' . htmlspecialchars($col) . '</th>';
                }
                $html .= '</tr></thead><tbody>';
                foreach ($results as $row) {
                    $html .= '<tr>';
                    foreach ($columns as $col) {
                        $html .= '<td style="padding:7px 14px; color:#222; background:#fff;">' . htmlspecialchars($row->$col) . '</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</tbody></table></div>';
            }
        } catch (\Exception $e) {
            $html = '<div style="
                max-width: 85%;
                background-color: #f8d7da;
                color: #ff0000;
                border-radius: 0.5rem;
                display: inline-block;
                font-size: 14px;
                padding: 8px 10px;
                margin-bottom: 16px;
            ">Query error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }

        if ($request->ajax()) {
            return response()->json(['html' => $html]);
        }
        return back()->with('query_result', $html)->withInput();
    }

    public function getStudentProgressByTopic($userId, $topicId)
    {
        // Ambil enroll aktif
        $enroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();
        $enrollId = $enroll ? $enroll->id : null;

        // Ambil semua id subtopik pada topik ini
        $subtopicIds = DB::table('mysql_topic_details')
            ->where('topic_id', $topicId)
            ->pluck('id');

        // Hitung total expected answer (total_question) pada semua subtopik
        $totalAnswer = DB::table('mysql_topic_details')
            ->where('topic_id', $topicId)
            ->sum('total_question');

        // Hitung jumlah submission status=true pada semua subtopik topik ini dan enroll aktif
        $correctSubmissions = DB::table('mysql_student_submissions')
            ->where('user_id', $userId)
            ->where('status', 'true')
            ->whereIn('topic_detail_id', $subtopicIds)
            ->where('enroll_id', $enrollId)
            ->count();

        // Hitung persentase progress
        $progressPercent = $totalAnswer > 0 ? round(($correctSubmissions / $totalAnswer) * 100) : 0;

        return $progressPercent;
    }

    public function getStudentProgressAjax(Request $request)
    {
        $userId = Auth::user()->id;
        $mysqlid = (int) $request->get('mysqlid');
        $enroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $mysqlid)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();
        $enrollId = $enroll ? $enroll->id : null;
        $progressPercent = $this->getStudentProgressByEnroll($userId, $mysqlid, $enrollId);
        return response()->json(['progress' => $progressPercent]);
    }

    // Method untuk mengambil data sidebar checklist
    public function sidebarAjax(Request $request)
    {
        $mysqlid = $request->get('mysqlid');
        $detailId = $request->get('start');
        $userId = Auth::id();

        // Ambil enroll aktif terbaru
        $enroll = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $mysqlid)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();
        $enrollId = $enroll ? $enroll->id : null;

        // Pastikan progressPercent dihitung dari enrollId terbaru
        $progressPercent = $this->getStudentProgressByEnroll($userId, $mysqlid, $enrollId);

        $detailCount = DB::table('mysql_topic_details')->where('topic_id', $mysqlid)->count();
        $detail = DB::table('mysql_topic_details')->where('id', $detailId)->first();
        $pdf_reader = 0;
        $html_start = '';
        $rows = DB::table('mysql_topic_details')->where('topic_id', $mysqlid)->get();

        return view('mysql_dml.student.material.sidebar', compact(
            'mysqlid',
            'detail',
            'progressPercent',
            'detailCount',
            'pdf_reader',
            'html_start',
            'rows',
            'detailId',
            'enrollId'
        ))->render();
    }

    public function finishTopic(Request $request)
    {
        $userId = Auth::id();
        $topicId = $request->input('mysqlid');
        $now = now();

        // Ambil enroll aktif (belum selesai)
        $topicTime = DB::table('mysql_student_topic_times')
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('is_finished', 0)
            ->orderByDesc('id')
            ->first();

        if ($topicTime && $topicTime->started_at && !$topicTime->duration_seconds) {
            $duration = $now->diffInSeconds(\Carbon\Carbon::parse($topicTime->started_at));
            DB::table('mysql_student_topic_times')
                ->where('id', $topicTime->id)
                ->update([
                    'duration_seconds' => $duration,
                    'is_finished' => 1,
                    'updated_at' => $now
                ]);

            // Tambahkan update is_reset di sini
            DB::table('mysql_user_reset')->updateOrInsert(
                ['user_id' => $userId, 'topic_id' => $topicId],
                ['is_reset' => true]
            );
        }

        return response()->json(['success' => true]);
    }

    public function resetTestingDatabase(Request $request)
    {
        $userId = Auth::id();
        $mysqlid = $request->get('mysqlid');

        // Nama database user
        $dbName = "iclop_user_" . $userId;

        // 1. Drop database user saja, tanpa create ulang atau import template
        try {
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal drop database: ' . $e->getMessage()]);
        }

        // 2. Hapus file acceptance_user dan query_user milik user
        $suiteFile = base_path("tests/acceptance_user_{$userId}.suite.yml");
        if (file_exists($suiteFile)) {
            @unlink($suiteFile);
        }

        // 3. Simpan status reset
        DB::table('mysql_user_reset')->updateOrInsert(
            ['user_id' => $userId, 'topic_id' => $mysqlid],
            ['is_reset' => true]
        );

        return response()->json(['success' => true, 'message' => 'Database testing berhasil dihapus!']);
    }

    private function addDefaultMessage($validationError, $isCorrect = false)
    {
        if ($isCorrect) {
            $defaultMessage = "Congratulations! Your query is correct.";
        } else {
            $defaultMessage = "Your query does not match. Please check again!";
        }

        // Jika pesan error kosong, return pesan default saja
        if (empty($validationError)) {
            return $defaultMessage;
        }

        // Jika pesan sudah mengandung pesan default, jangan tambahkan lagi
        if (stripos($validationError, $defaultMessage) !== false) {
            return $validationError;
        }

        // Tambahkan pesan default di bawah pesan error yang ada dengan 1 baris kosong
        return $validationError . "<br>" . $defaultMessage;
    }
}
