<?php

use App\Http\Controllers\React\ReactController;
use App\Http\Controllers\React\ReactDosenController;
use App\Http\Controllers\React\Student\ReactLogicalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\PHP\PHPController;
use App\Http\Controllers\PHP\PHPDosenController;
use App\Http\Controllers\PHP\Student\DashboardUnitControllers;
use App\Http\Controllers\PHP\Student\StudikasusController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => ['auth']], function() {
    Route::prefix('react')->group(function () {
        Route::get('/start', [ReactController::class, 'index'])->name('react_welcome');
        Route::get('/detail-topics', [ReactController::class, 'php_material_detail'])->name('react_material_detail');
        Route::get('/php-admin', [ReactController::class, 'php_admin'])->name('php_admin');
        Route::post('/uploadimage', [ReactController::class, 'upload'])->name('uploadimage');
        Route::get('/send-task', [ReactController::class, 'send_task'])->name('send_task');
        Route::post('/session_progress', [ReactController::class, 'session_progress'])->name('session_progress');
        Route::post('/task/submission', [ReactController::class, 'task_submission'])->name('task_submission');
        Route::get('/result-task', [ReactController::class, 'result_task'])->name('result_task');
        Route::get('/result-test-student', [ReactController::class, 'result_test'])->name('phpunit.result-test-student');
        Route::any('/akhir-ujian', [ReactController::class, 'unittesting'])->name('unittesting');
        Route::any('/baru/submit_score', [ReactController::class, 'submit_score_baru'])->name('submit_score_baru');
        Route::post('/upload-file', [ReactLogicalController::class, 'uploadFile'])->name('upload_file');
    });
});

Route::group(['middleware' => ['auth', 'teacher']], function() {
    Route::prefix('react')->group(function () {

        Route::get('/teacher/topics',[ReactDosenController::class, 'topics'])->name('react_teacer_topics');
        Route::get('/teacher/topics/add/{id}', [ReactDosenController::class, 'add_topics'])->name('react_teacer_topics');
        Route::post('/teacher/topics/simpan', [ReactDosenController::class, 'simpan'])->name('react_teacer_simpan');
    });

});
