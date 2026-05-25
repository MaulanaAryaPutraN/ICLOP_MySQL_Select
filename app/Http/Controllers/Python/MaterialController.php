<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Material;
use App\Models\StudentSubmission;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
   

    public function index()
    {
        $materials = Material::all(); // atau query lain sesuai kebutuhan
        return view('learning_student', compact('materials'));
    }
    public function materialDetail(Request $request)
    {
        $materialId = $request->query('material_id');
        $material = Material::findOrFail($materialId);
        
        return view('material_detail', compact('material'));
    }
    public function showTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $submission = StudentSubmission::where('user_id', auth()->id())
                                       ->where('task_id', $taskId)
                                       ->latest()
                                       ->first();

        $submittedCode = '';
        if ($submission && $submission->file_path) {
            $submittedCode = Storage::get($submission->file_path);
        }

        return view('task_detail', compact('task', 'submission', 'submittedCode'));
    }
    
    public function downloadPdf(Task $task)
    {
        $pdfPath = 'pdfs/' . $task->pdf_path;
        if (Storage::disk('public')->exists($pdfPath)) {
            return Storage::disk('public')->download($pdfPath);
        }
        abort(404, 'PDF tidak ditemukan');
    }
    
}