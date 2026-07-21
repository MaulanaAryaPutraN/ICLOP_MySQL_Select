<?php
// =============================================
// File: app/Http/Controllers/Select/SelectTeacherTopicsController.php
// =============================================
namespace App\Http\Controllers\Select;

use App\Http\Controllers\Controller;
use App\Models\Select\SelectTopicDetails;
use App\Models\Select\SelectTopics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SelectTeacherTopicsController extends Controller
{
    public function index()
    {
        $data = DB::table('select_topics as t')
            ->join('select_topic_details as td', 'td.topic_id', '=', 't.id')
            ->select('t.title as topic_title', 'td.title as sub_topic_title', 'td.file_name', 'td.file_path')
            ->orderBy('t.id')->orderBy('td.id')->get();

        return view('select.teacher.index', compact('data'));
    }

    public function topicsTable()
    {
        $data1 = SelectTopics::with(['topicDetails', 'createdBy'])->get();
        return view('select.teacher.table.topics_table', compact('data1'));
    }

    // ------------------------------------------------------------------
    // ADD TOPIC + SUBTOPIC
    // Setelah topic disimpan → langsung buat db_kuliah,
    // import schema dosen + install myTAP (functions & procedure
    // langsung masuk ke db_kuliah, tidak pakai database tap terpisah).
    // Sehingga saat mahasiswa mulai mengerjakan, database sudah siap.
    // ------------------------------------------------------------------
    public function addTopicSubtopic(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'topic_title'       => 'required|string|max:255',
            'countdown_minutes' => 'required|integer|min:1',
            'sub_topic_title'   => 'required|array|min:1',
            'sub_topic_title.*' => 'required|string|max:255',
            'sub_topic_file.*'  => 'nullable|file|mimes:pdf|max:20480',
            'schema_file'       => 'required|file|mimes:sql,txt|max:20480',
        ]);

        // Simpan file schema dosen
        $file           = $request->file('schema_file');
        $schemaFileName = $file->getClientOriginalName();
        $schemaFilePath = 'select/schema/';
        $file->move(public_path($schemaFilePath), $schemaFileName);

        // Simpan topic
        $topic = new SelectTopics();
        $topic->title             = $request->topic_title;
        $topic->countdown_seconds = $request->countdown_minutes * 60;
        $topic->created_by        = $userId;
        $topic->is_sequential     = $request->has('is_sequential') ? 1 : 0;
        $topic->schema_file_name  = $schemaFileName;
        $topic->schema_file_path  = $schemaFilePath;
        $topic->save();

        // Simpan subtopik
        $files = $request->hasFile('sub_topic_file') ? $request->file('sub_topic_file') : [];
        foreach ($request->sub_topic_title as $i => $subtopicTitle) {
            $fileName = null;
            $filePath = null;
            if (isset($files[$i]) && $files[$i]) {
                $f        = $files[$i];
                $fileName = $f->getClientOriginalName();
                $filePath = 'select/modul/';
                $f->move(public_path($filePath), $fileName);
            }
            SelectTopicDetails::create([
                'topic_id'   => $topic->id,
                'title'      => $subtopicTitle,
                'file_name'  => $fileName,
                'file_path'  => $filePath,
                'created_by' => $userId,
            ]);
        }

        // Buat database testing + install myTAP setelah topic tersimpan
        // Mahasiswa tidak perlu menunggu saat mulai mengerjakan
        $this->createTestingDatabase($topic->id, public_path($schemaFilePath . $schemaFileName));

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('select_teacher')->with('success', 'Topic & Sub-Topic saved!');
    }

    // ------------------------------------------------------------------
    // UPDATE TOPIC
    // Jika schema_file diupload ulang → drop db lama, buat ulang
    // ------------------------------------------------------------------
    public function updateTopicAjax(Request $request, $id)
    {
        $topic = SelectTopics::findOrFail($id);

        $request->validate([
            'topic_title'       => 'required|string|max:255',
            'countdown_minutes' => 'required|integer|min:1',
            'edit_schema_file'  => 'nullable|file|mimes:sql,txt|max:20480',
        ]);

        $topic->title             = $request->topic_title;
        $topic->countdown_seconds = $request->countdown_minutes * 60;
        $topic->is_sequential     = $request->has('is_sequential') ? 1 : 0;

        $schemaUpdated = false;

        if ($request->hasFile('edit_schema_file')) {
            // Hapus file schema lama
            if ($topic->schema_file_name && $topic->schema_file_path) {
                @unlink(public_path($topic->schema_file_path . $topic->schema_file_name));
            }

            $file           = $request->file('edit_schema_file');
            $schemaFileName = $file->getClientOriginalName();
            $schemaFilePath = 'select/schema/';
            $file->move(public_path($schemaFilePath), $schemaFileName);

            $topic->schema_file_name = $schemaFileName;
            $topic->schema_file_path = $schemaFilePath;
            $schemaUpdated = true;
        }

        $topic->save();

        // Update subtopik
        $ids    = $request->sub_topic_ids ?? [];
        $titles = $request->sub_topic_titles ?? [];
        $files  = $request->file('edit_sub_topic_file', []);

        SelectTopicDetails::where('topic_id', $id)
            ->whereNotIn('id', array_filter($ids))
            ->each(function ($sub) {
                if ($sub->file_name && $sub->file_path) {
                    @unlink(public_path(rtrim($sub->file_path, '/\\') . DIRECTORY_SEPARATOR . $sub->file_name));
                }
                $sub->delete();
            });

        foreach ($titles as $i => $title) {
            if (!empty($ids[$i])) {
                $sub = SelectTopicDetails::find($ids[$i]);
                if ($sub) {
                    $sub->title = $title;
                    if (isset($files[$i]) && $files[$i]) {
                        if ($sub->file_name && $sub->file_path) {
                            @unlink(public_path(rtrim($sub->file_path, '/\\') . DIRECTORY_SEPARATOR . $sub->file_name));
                        }
                        $f = $files[$i];
                        $sub->file_name = $f->getClientOriginalName();
                        $sub->file_path = 'select/modul/';
                        $f->move(public_path($sub->file_path), $sub->file_name);
                    }
                    $sub->save();
                }
            } else {
                $fileName = null;
                $filePath = null;
                if (isset($files[$i]) && $files[$i]) {
                    $f        = $files[$i];
                    $fileName = $f->getClientOriginalName();
                    $filePath = 'select/modul/';
                    $f->move(public_path($filePath), $fileName);
                }
                SelectTopicDetails::create([
                    'topic_id'   => $id,
                    'title'      => $title,
                    'file_name'  => $fileName,
                    'file_path'  => $filePath,
                    'created_by' => auth()->id(),
                ]);
            }
        }

        // Jika schema diupdate → drop db lama dan buat ulang
        if ($schemaUpdated) {
            $this->dropTestingDatabase();
            $this->createTestingDatabase($id, public_path($topic->schema_file_path . $topic->schema_file_name));
        }

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // DELETE TOPIC → hapus juga database testing-nya
    // ------------------------------------------------------------------
    public function deleteTopic($id)
    {
        $topic = SelectTopics::findOrFail($id);

        // Hapus file schema
        if ($topic->schema_file_name && $topic->schema_file_path) {
            @unlink(public_path(rtrim($topic->schema_file_path, '/\\') . DIRECTORY_SEPARATOR . $topic->schema_file_name));
        }

        // Hapus file PDF subtopik
        foreach ($topic->topicDetails as $subtopic) {
            if ($subtopic->file_name && $subtopic->file_path) {
                @unlink(public_path(rtrim($subtopic->file_path, '/\\') . DIRECTORY_SEPARATOR . $subtopic->file_name));
            }
        }

        $topic->topicDetails()->delete();
        $topic->delete();

        // Hapus database testing db_kuliah
        $this->dropTestingDatabase();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('select_teacher')->with('success', 'Topic berhasil dihapus.');
    }

    public function editTopicAjax($id)
    {
        $topic     = SelectTopics::findOrFail($id);
        $subtopics = SelectTopicDetails::where('topic_id', $id)->get();
        return response()->json(['topic' => $topic, 'subtopics' => $subtopics]);
    }

    public function deleteSubtopic($id)
    {
        $subtopic = SelectTopicDetails::findOrFail($id);
        if ($subtopic->file_name && $subtopic->file_path) {
            $path = public_path(rtrim($subtopic->file_path, '/\\') . DIRECTORY_SEPARATOR . $subtopic->file_name);
            if (file_exists($path)) @unlink($path);
        }
        $subtopic->delete();
        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // BUAT DATABASE TESTING + INSTALL myTAP
    //
    // Dipanggil saat dosen add topic (atau update schema).
    // db_kuliah dibuat, schema diimport, myTAP diinstall.
    //
    // Urutan install:
    //   1. Buat database db_kuliah
    //   2. Import schema dosen
    //   3. Install mytap_setup.sql  → buat _tap_counters + fungsi ok()/plan()
    //      langsung di db_kuliah (tidak butuh database tap terpisah)
    //   4. Install mytap_runner_select.sql → buat procedure test_select_query
    //
    // Sehingga saat mahasiswa buka materi, database SUDAH SIAP.
    // ------------------------------------------------------------------
    private function createTestingDatabase(int $topicId, string $schemaPath): void
    {
        $dbName  = "db_kuliah";
        $dbHost  = config('database.connections.mysql.host');
        $dbPort  = config('database.connections.mysql.port', 3306);
        $dbUser  = config('database.connections.mysql.username');
        $dbPass  = config('database.connections.mysql.password');
        $passArg = $dbPass ? "-p\"{$dbPass}\"" : "";

        // Path file myTAP — taruh di database/sql/
        $mytapSetup  = base_path('database/sql/mytap_setup.sql');
        $mytapRunner = base_path('database/sql/mytap_runner_select.sql');

        try {
            // 1. Buat database testing db_kuliah
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // 2. Import schema dosen ke database testing
            $importCmd = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passArg} {$dbName} < \"{$schemaPath}\"";
            shell_exec($importCmd);

            // 3. Install myTAP setup: buat tabel _tap_counters + fungsi ok()/plan()
            //    Semua objek TAP masuk ke db_kuliah langsung (tidak ada database tap terpisah)
            $setupCmd = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passArg} {$dbName} < \"{$mytapSetup}\"";
            shell_exec($setupCmd);

            // 4. Install myTAP runner: buat procedure test_select_query + fungsi validasi
            $runnerCmd = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passArg} {$dbName} < \"{$mytapRunner}\"";
            shell_exec($runnerCmd);

            Log::info("Database {$dbName} berhasil dibuat: schema diimport + myTAP terinstall (table-based, tanpa database tap terpisah).");
        } catch (\Exception $e) {
            Log::error("Gagal buat database {$dbName}: " . $e->getMessage());
            throw $e;
        }
    }

    // ------------------------------------------------------------------
    // DROP DATABASE TESTING
    // Dipanggil saat dosen hapus topic atau update schema.
    // Menggunakan shell_exec untuk reliability (hindari active connection issue).
    // ------------------------------------------------------------------
    private function dropTestingDatabase(): void
    {
        $dbName  = "db_kuliah";
        $dbHost  = config('database.connections.mysql.host');
        $dbPort  = config('database.connections.mysql.port', 3306);
        $dbUser  = config('database.connections.mysql.username');
        $dbPass  = config('database.connections.mysql.password');
        $passArg = $dbPass ? "-p\"{$dbPass}\"" : "";

        try {
            // Gunakan shell_exec agar tidak terpengaruh koneksi Laravel yang aktif
            $dropCmd = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passArg} "
                . "-e \"DROP DATABASE IF EXISTS \`{$dbName}\`\"";
            shell_exec($dropCmd);

            // Verifikasi: pastikan DB benar-benar sudah tidak ada
            $exists = DB::select(
                "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?",
                [$dbName]
            );

            if (empty($exists)) {
                Log::info("Database {$dbName} berhasil dihapus.");
            } else {
                // Fallback: coba lewat Laravel DB statement
                DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
                Log::warning("Database {$dbName}: shell_exec gagal, fallback ke DB::statement.");
            }
        } catch (\Exception $e) {
            Log::error("Gagal hapus database {$dbName}: " . $e->getMessage());
        }
    }
}
