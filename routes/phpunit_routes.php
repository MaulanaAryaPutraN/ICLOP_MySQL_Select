<?php

use App\Http\Controllers\PHP\Student\WelcomeController;
use App\Http\Controllers\PHP\Student\DashboardUnitControllers;
use App\Http\Controllers\PHP\Student\StudikasusController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

Route::prefix('phpunit')->group(function () {
   
    Route::post('/palm-testing', [WelcomeController::class, 'palm_testing'])->name('phpunit.palm_testing');
    Route::get('/unit-testing', [WelcomeController::class, 'unittesting'])->name('phpunit.unit-testing');
    Route::get('/result-test-student', [WelcomeController::class, 'result_test'])->name('phpunit.result-test-student');
    Route::any('/form-upload', [WelcomeController::class, 'form_upload'])->name('phpunit.form-upload');
    Route::any('/proses', [WelcomeController::class, 'proses_upload'])->name('phpunit.proses');
    // Studikasus
    Route::prefix('studi-kasus')->controller(StudikasusController::class)->group(function () {
        Route::get('/', 'index')->name('studi-kasus');
        Route::get('/projects/{id}', 'projects')->name('studi-kasus.projects');
        Route::any('/upload_jawaban', [StudikasusController::class, 'upload_jawaban'])->name('studi-kasus.upload_jawaban');
        Route::any('/akhir-ujian', [StudikasusController::class, 'unittesting'])->name('studi-kasus.akhir-ujian');
        Route::get('/upload-test-student', [StudikasusController::class, 'upload_test'])->name('studi-kasus.upload-test-student');
    });
});
