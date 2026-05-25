<?php

use App\Http\Controllers\PHP\Student\WelcomeController;
use App\Http\Controllers\PHP\Student\DashboardUnitControllers;
use App\Http\Controllers\PHP\Student\StudikasusController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-student', function () {
        return view('dashboard_student');
    })->name('dashboard-student');


    Route::get('/learning-student', [MaterialController::class, 'index'])->name('learning_student');

    Route::get('/material-detail', [MaterialController::class, 'materialDetail'])->name('material_detail');
    Route::get('/task/{taskId}', [MaterialController::class, 'showTask'])->name('task.show');

    Route::post('/student-submission', [StudentSubmissionController::class, 'store'])->name('student.submission.store');
    // Route::get('/student-submission/{id}', [StudentSubmissionController::class, 'show'])->name('student.submission.show');
    // Route::get('/submissions/{id}', [StudentSubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/task/{task}/download-pdf', [MaterialController::class, 'downloadPdf'])->name('task.download-pdf');
    Route::post('/logoutt', [AuthController::class, 'logoutt'])->name('logoutt');
    
    Route::post('/store-python-result-data', [StudentSubmissionController::class, 'storeTestResult'])->name('store_python_result_data');
});