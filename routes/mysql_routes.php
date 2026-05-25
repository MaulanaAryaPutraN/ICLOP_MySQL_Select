<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MySQL\MysqlController;
use App\Http\Controllers\MySQL\MysqlStudentController;
use App\Http\Controllers\MySQL\MysqlTeacherAnswerKeyController;
use App\Http\Controllers\MySQL\MysqlTeacherSubmissionController;
use App\Http\Controllers\MySQL\MysqlTeacherTopicsController;
use App\Http\Controllers\MySQL\TopicDetailController;
use App\Http\Controllers\MySQL\MysqlTeacherQuestionController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('mysql')->group(function () {
        Route::get('/start', [MysqlController::class, 'index'])->name('mysql_welcome');
        Route::get('/detail-topics', [MysqlStudentController::class, 'showTopicDetail'])->name('showTopicDetail');
        Route::post('/submit', [MysqlStudentController::class, 'submitUserInput'])->name('submitUserInput');
        Route::post('/run-user-select-query', [MysqlStudentController::class, 'runUserSelectQuery'])->name('runUserSelectQuery');
        Route::get('/student/progress', [MysqlStudentController::class, 'getStudentProgressAjax'])->name('student.progress.ajax');
        Route::post('/student/import-data', [MysqlStudentController::class, 'importSqlData'])->name('student.import.sql');
        Route::get('/student/sidebar', [MysqlStudentController::class, 'sidebarAjax'])->name('student.sidebar.ajax'); // sidebar checklist ajax
        Route::post('/student/reset-testing-db', [MysqlStudentController::class, 'resetTestingDatabase'])->name('student.reset.testing.db');
        Route::post('/student/enroll-topic', [MysqlStudentController::class, 'enrollTopic'])->name('student.enroll.topic');
        Route::post('/student/finish-topic', [MysqlStudentController::class, 'finishTopic'])->name('student.finish.topic');
    });
});

Route::group(['middleware' => ['auth', 'teacher']], function () {
    Route::prefix('mysql')->group(function () {
        Route::get('/teacher/materials', [MysqlTeacherTopicsController::class, 'index'])->name('mysql_teacher');
        Route::get('/teacher/topics-table', [MysqlTeacherTopicsController::class, 'topicsTable'])->name('teacher.topics.table');
        Route::post('/teacher/topics/add-topic-subtopic', [MysqlTeacherTopicsController::class, 'addTopicSubtopic'])->name('teacher.topics.addTopicSubtopic');
        Route::delete('/teacher/topics/{id}/delete', [MysqlTeacherTopicsController::class, 'deleteTopic'])->name('teacher.topics.delete');
        // Route untuk ambil data topic & subtopic
        Route::get('/teacher/topics/{id}/edit', [MysqlTeacherTopicsController::class, 'editTopicAjax']);
        Route::put('/teacher/topics/{id}', [MysqlTeacherTopicsController::class, 'updateTopicAjax']);
        Route::delete('/teacher/subtopics/{id}/delete', [MysqlTeacherTopicsController::class, 'deleteSubtopic'])->name('teacher.subtopics.delete');
        // Route baru untuk hasil submission mahasiswa
        Route::get('/teacher/submissions', [MysqlTeacherSubmissionController::class, 'index'])->name('teacher.student.submissions');

        Route::get('/teacher/questions/table', [MysqlTeacherQuestionController::class, 'questionsTable'])->name('teacher.questions.table');
        Route::post('/teacher/questions/save', [MysqlTeacherQuestionController::class, 'saveQuestion'])->name('teacher.questions.save');
        Route::delete('/teacher/questions/delete/{id}', [MysqlTeacherQuestionController::class, 'deleteQuestion']);
        Route::get('/teacher/questions/list', [MysqlTeacherQuestionController::class, 'getQuestionList']);
        Route::get('/teacher/subtopics/list', [MysqlTeacherQuestionController::class, 'getSubtopicList']);
        Route::get('/teacher/topics/list', [MysqlTeacherQuestionController::class, 'getTopicList']);
    });
});
