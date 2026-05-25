<?php

namespace App\Http\Controllers;

use App\Models\StudentSubmission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class StudentSubmissionController extends Controller
{
    
    public function store(Request $request)
    {

        // dd($request->all());
        // $data = 
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'file' => 'required|file|mimes:txt,py' // Validasi untuk file Python
        ]);

        // dd($data);

        $user = auth()->user();
        $task = Task::findOrFail($request->task_id);
        $uploadedFile = $request->file('file');
        $expectedFileName = $this->getExpectedFileName($task);

        // Simpan file dengan nama yang ditentukan
        $filePath = $uploadedFile ->storeAs('./public/submissions', $expectedFileName, 'local');
        
        //Menambahkan validasi nama file sebelum menyimpan file:
        if (!$this->isFileNameValid($uploadedFile->getClientOriginalName(), $expectedFileName)) {
            return redirect()->back()->with('error', 'Nama file tidak sesuai. Harap gunakan format: ' . $expectedFileName);
        }
        
        
        // Log untuk memastikan file tersimpan dengan benar
        \Log::info('File saved at: ' . $filePath);
        \Log::info('File content after save: ' . Storage::disk('public')->get($filePath));

    // Tambahkan kode debugging di sini
        \Log::info('File uploaded: ' . $filePath);
        if (Storage::disk('local')->exists($filePath)) {
            \Log::info('File exists at: ' . Storage::disk('local')->path($filePath));
        } else {
            \Log::error('File not found at: ' . Storage::disk('local')->path($filePath));
        }


        $task = Task::findOrFail($request->task_id);
        $existingSubmission = StudentSubmission::where('user_id', $user->id)
                                               ->where('task_id', $task->id)
                                               ->first();
        if ($existingSubmission) {
            // Jika sudah ada, update submission yang ada
            $submissionCount = $existingSubmission->submission_count + 1;
            $existingSubmission->update([
                'submission_count' => $submissionCount,
                'file_path' => $filePath,
            ]);
        } else {
            // Jika belum ada, buat submission baru
            $submissionCount = 1;
            StudentSubmission::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'submission_count' => $submissionCount,
                'file_path' => $filePath,
            ]);
        }

        // Di sini Anda bisa menambahkan logika untuk menjalankan tes
        // dan menyimpan hasilnya ke $submission->test_result

        // return redirect()->back()->with('success', 'Submission berhasil.');
    }
    // }
    private function getExpectedFileName(Task $task)
    { // mengekstrak nama file pdf dan merubah namanya menjadi answer_{path}.py
        // untuk pathnya adalah path dari bab dan percobaan nya
        // misalnya jika pathnya adalah bab1_Percobaan1 maka nama file nya akan answer_bab1_Percobaan1.py
        // dari hasil ini kemudian akan jadi pembanding untuk nama file yang diupload

        // Ambil nama file PDF dari kolom pdf_path pada tabel tasks
        $pdfPath = $task->pdf_path;

        // Ekstrak nama file dari path
        $pdfFileName = basename($pdfPath);

        // Ekstrak bagian yang diperlukan (misalnya 'bab1_Percobaan1')
        preg_match('/bab\d+_Percobaan\d+/i', $pdfFileName, $matches);
        $extractedPart = $matches[0] ?? '';

        // Buat nama file yang diharapkan
        return 'answer_' . strtolower($extractedPart) . '.py';
    }

    private function isFileNameValid($uploadedFileName, $expectedFileName)
    {
        return strtolower($uploadedFileName) === strtolower($expectedFileName);
    }

    public function storeTestResult(Request $request)
    {
        // Validasi request jika diperlukan
        $request->validate([
            'output' => 'required|string',
            'task_id' => 'required|exists:tasks,id',
        ]);

        // Mendapatkan pengguna yang sedang login
        $user_id = auth()->user()->id;

        // Mendapatkan data dari request
        $output = $request->input('output');
        $task_id = $request->input('task_id');

        // Mencari submission yang sesuai
        $submission = StudentSubmission::where('user_id', $user_id)
                                    ->where('task_id', $task_id)
                                    ->first();

        if ($submission) {
            // Update kolom test_result dengan data output
            $submission->update([
                'test_result' => $output,
            ]);

            // Mengembalikan respons sukses
            return response()->json(['message' => 'Data berhasil disimpan.', 'data' => $submission]);
        } else {
            // Jika submission tidak ditemukan, kembalikan respons error
            return response()->json(['message' => 'Submission tidak ditemukan.'], 404);
            }
        }

//   public function show($id)
//     {
//         $submission = StudentSubmission::findOrFail($id);
//         $task = Task::findOrFail($id);
//         return view('task_detail', compact('submission','task'));
//     }

    private function runTest(StudentSubmission $submission)
    {
        $testFilePath = storage_path('app/public/test_file/test_' . $submission->task_id . '.py');
        $submissionFilePath = storage_path('app/' . $submission->file_path);

        $process = new Process(['python', $testFilePath, $submissionFilePath]);
        $process->run();

        return $process->getOutput();
    }
}