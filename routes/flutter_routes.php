<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Flutter\PHPController;
use App\Http\Controllers\Flutter\FlutterController;
use App\Http\Controllers\PHP\PHPDosenController;
use App\Http\Controllers\PHP\Student\DashboardUnitControllers;
use App\Http\Controllers\PHP\Student\StudikasusController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => ['auth', 'student']], function () {
    Route::prefix('flutter')->group(function () {

        Route::get('/start', [FlutterController::class, 'index'])->name('flutterHome');
        Route::get('/detail-topics', [FlutterController::class, 'flutter_material_detail'])->name('flutter_material_detail');
        Route::post('/detail-topics', [FlutterController::class, 'store'])->name('store_flutter_result_data');

        // Route::get('/start', [PHPController::class, 'index'])->name('welcome');
        // Route::get('/detail-topics', [PHPController::class, 'php_material_detail'])->name('php_material_detail');
        // Route::get('/php-admin', [PHPController::class, 'php_admin'])->name('php_admin');
        // Route::post('/uploadimage',[PHPController::class, 'upload'])->name('uploadimage');
        // Route::get('/send-task',[PHPController::class, 'send_task'])->name('send_task');
        // Route::post('/session_progress',[PHPController::class, 'session_progress'])->name('session_progress');
        // Route::post('/task/submission',[PHPController::class, 'task_submission'])->name('task_submission');
        // Route::get('/result-task',[PHPController::class, 'result_task'])->name('result_task');
        // Route::get('/result-test-student', [PHPController::class, 'result_test'])->name('phpunit.result-test-student');
        // Route::any('/akhir-ujian', [PHPController::class, 'unittesting'])->name('unittesting');
    });
});

Route::group(['middleware' => ['auth', 'student']], function () {
    Route::prefix('php')->group(function () {

        // Route::get('/teacher/topics',[PHPDosenController::class, 'topics'])->name('topics');
        // Route::get('/teacher/topics/add/{id}', [PHPDosenController::class, 'add_topics'])->name('topics');
        // Route::post('/teacher/topics/simpan', [PHPDosenController::class, 'simpan'])->name('simpan');
    });
});