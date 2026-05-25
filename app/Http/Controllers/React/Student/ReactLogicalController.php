<?php

namespace App\Http\Controllers\React\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\React\ReactSubmitUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReactLogicalController extends Controller
{
    // function upload file
    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                'uploadFile' => 'required|file|max:1024',
            ]);

            if ($request->hasFile('uploadFile')) {
                $uploadedFile = $request->file('uploadFile');
                $userName = Auth::user()->name;
                $fileName = $uploadedFile->getClientOriginalName();
                $uploadedFile->storeAs('private/' . $userName . '/React', $fileName);

                $materiType = $this->getMateriType($fileName);
                $comparisonResult = $this->compareFile($materiType, $uploadedFile);

                // Menyimpan data ke database dengan status berdasarkan hasil perbandingan
                try {
                    $submitUser = new ReactSubmitUser();
                    $submitUser->id_user = Auth::id();
                    $submitUser->nama_user = $userName;
                    $submitUser->materi = 'React - ' . $fileName;
                    $submitUser->nilai = $comparisonResult === 'Congratulations, your answer is correct.' ? 100 : 0;
                    $submitUser->status = $comparisonResult === 'Congratulations, your answer is correct.' ? 'True' : 'False';
                    $submitUser->save();
                } catch (\Exception $dbException) {
                    // Log error and return response
                    Log::error('Database save failed: ' . $dbException->getMessage());
                    return response()->json([
                        'error' => 'Database save failed.',
                        'message' => 'There was an error saving your result to the database.',
                    ], 500);
                }

                return response()->json([
                    'uploaded' => true,
                    'fileName' => $fileName,
                    'message' => 'File uploaded successfully.',
                    'comparisonResult' => $comparisonResult,
                ]);
            } else {
                return response()->json([
                    'error' => 'File upload failed.',
                    'message' => 'The upload file field is required.',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'File upload failed.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // function menentukan tipe materi
    private function getMateriType($fileName)
    {
        if (strpos($fileName, 'Hello.js') !== false) {
            return 'hello';
        } elseif (strpos($fileName, 'Form.js') !== false) {
            return 'form';
        } elseif (strpos($fileName, 'Counter.js') !== false) {
            return 'counter';
        } elseif (strpos($fileName, 'FormStyle.js') !== false) {
            return 'formStyle';
        } elseif (strpos($fileName, 'Navbar.js') !== false) {
            return 'navbar';
        } else {
            return 'unknown';
        }
    }

    // function mengecek jawaban
    private function compareFile($materiType, $uploadedFile)
    {
        switch ($materiType) {
            case 'hello':
                return $this->jawabanHello($uploadedFile);
            case 'form':
                return $this->jawabanForm($uploadedFile);
            case 'counter':
                return $this->jawabanCounter($uploadedFile);
            case 'formStyle':
                return $this->jawabanFormStyle($uploadedFile);
            case 'navbar':
                return $this->jawabanNavbar($uploadedFile);
            default:
                throw new \Exception('Harus Sesuai Materi');
        }
    }

    // function mencari perbedaan
    private function getDifference($uploadedContent, $answerKeyContent)
    {
        // Membandingkan baris per baris untuk menemukan perbedaan
        $uploadedLines = explode("\n", $uploadedContent);
        $answerKeyLines = explode("\n", $answerKeyContent);

        $diffLines = [];

        foreach ($uploadedLines as $lineNumber => $line) {
            if (!isset($answerKeyLines[$lineNumber]) || $line !== $answerKeyLines[$lineNumber]) {
                $diffLines[] = [
                    'line_number' => $lineNumber + 1,
                    'uploaded_line' => $line,
                    'answer_key_line' => isset($answerKeyLines[$lineNumber]) ? $answerKeyLines[$lineNumber] : '',
                ];
            }
        }

        $diffMessage = '';
        foreach ($diffLines as $diffLine) { 
            $diffMessage .= "Line{$diffLine['line_number']}: \n";
            $diffMessage .= "Your Answer: {$diffLine['uploaded_line']} \n";
            $diffMessage .= "Should: {$diffLine['answer_key_line']} \n\n";
        }

        return $diffMessage;
    }

    // function hello
    private function jawabanHello($uploadedFile)
    {
        $uploadedContent = file_get_contents($uploadedFile->getRealPath());

        $answerKeyPath = storage_path('app/private/_answerKey_/React/Hello.js');

        if (!file_exists($answerKeyPath)) {
            throw new \Exception('Answer key file not found.');
        }

        $answerKeyContent = file_get_contents($answerKeyPath);

        if ($uploadedContent === $answerKeyContent) {
            return 'Congratulations, your answer is correct.';
        } else {
            $diff = $this->getDifference($uploadedContent, $answerKeyContent);
            $errorMessage = 'Your answer is still wrong. Fix it again, OK? Differences in ' . $diff;
            return $errorMessage;
        }
    }

    // function form
    private function jawabanForm($uploadedFile)
    {
        $uploadedContent = file_get_contents($uploadedFile->getRealPath());

        $answerKeyPath = storage_path('app/private/_answerKey_/React/Form.js');

        if (!file_exists($answerKeyPath)) {
            throw new \Exception('Answer key file not found.');
        }

        $answerKeyContent = file_get_contents($answerKeyPath);

        if ($uploadedContent === $answerKeyContent) {
            return 'Congratulations, your answer is correct.';
        } else {
            $diff = $this->getDifference($uploadedContent, $answerKeyContent);
            $errorMessage = 'Your answer is still wrong. Fix it again, OK? Differences in ' . $diff;
            return $errorMessage;
        }
    }

    // function counter
    private function jawabanCounter($uploadedFile)
    {
        $uploadedContent = file_get_contents($uploadedFile->getRealPath());

        $answerKeyPath = storage_path('app/private/_answerKey_/React/Counter.js');

        if (!file_exists($answerKeyPath)) {
            throw new \Exception('Answer key file not found.');
        }

        $answerKeyContent = file_get_contents($answerKeyPath);

        if ($uploadedContent === $answerKeyContent) {
            return 'Congratulations, your answer is correct.';
        } else {
            $diff = $this->getDifference($uploadedContent, $answerKeyContent);
            $errorMessage = 'Your answer is still wrong. Fix it again, OK? Differences in ' . $diff;
            return $errorMessage;
        }
    }

    private function jawabanFormStyle($uploadedFile)
    {
        $uploadedContent = file_get_contents($uploadedFile->getRealPath());

        $answerKeyPath = storage_path('app/private/_answerKey_/React/FormStyle.js');

        if (!file_exists($answerKeyPath)) {
            throw new \Exception('Answer key file not found.');
        }

        $answerKeyContent = file_get_contents($answerKeyPath);

        if ($uploadedContent === $answerKeyContent) {
            return 'Congratulations, your answer is correct.';
        } else {
            $diff = $this->getDifference($uploadedContent, $answerKeyContent);
            $errorMessage = 'Your answer is still wrong. Fix it again, OK? Differences in ' . $diff;
            return $errorMessage;
        }
    }

    private function jawabanNavbar($uploadedFile)
    {
        $uploadedContent = file_get_contents($uploadedFile->getRealPath());

        $answerKeyPath = storage_path('app/private/_answerKey_/React/Navbar.js');

        if (!file_exists($answerKeyPath)) {
            throw new \Exception('Answer key file not found.');
        }

        $answerKeyContent = file_get_contents($answerKeyPath);

        if ($uploadedContent === $answerKeyContent) {
            return 'Congratulations, your answer is correct.';
        } else {
            $diff = $this->getDifference($uploadedContent, $answerKeyContent);
            $errorMessage = 'Your answer is still wrong. Fix it again, OK? Differences in ' . $diff;
            return $errorMessage;
        }
    }
    public function getComparisonResults($userId)
    {
        $results = ReactSubmitUser::where('id_user', $userId)->get();

        return response()->json($results);
    }

}
