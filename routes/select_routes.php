<?php
// =============================================
// File: routes/select_routes.php
// =============================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Select\SelectController;
use App\Http\Controllers\Select\SelectStudentController;
use App\Http\Controllers\Select\SelectTeacherTopicsController;
use App\Http\Controllers\Select\SelectTeacherSubmissionController;
use App\Http\Controllers\Select\SelectTeacherQuestionController;

// -----------------------------------------------
// STUDENT ROUTES
// -----------------------------------------------
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('select')->group(function () {
        Route::post('/select/student/pause-timer',  [SelectStudentController::class, 'pauseTimer'])->name('v2.student.pause.timer');
        Route::post('/select/student/resume-timer', [SelectStudentController::class, 'resumeTimer'])->name('v2.student.resume.timer');
        Route::get('/start',                    [SelectController::class, 'index'])->name('select_welcome');
        Route::get('/detail-topics',            [SelectStudentController::class, 'showTopicDetail'])->name('v2.showTopicDetail');
        Route::post('/submit',                  [SelectStudentController::class, 'submitUserInput'])->name('v2.submitUserInput');
        // Route::post('/run-user-select-query',   [SelectStudentController::class, 'runUserSelectQuery'])->name('v2.runUserSelectQuery');
        Route::get('/student/progress',         [SelectStudentController::class, 'getStudentProgressAjax'])->name('v2.student.progress.ajax');
        Route::get('/student/sidebar',          [SelectStudentController::class, 'sidebarAjax'])->name('v2.student.sidebar.ajax');
        Route::post('/student/enroll-topic',    [SelectStudentController::class, 'enrollTopic'])->name('v2.student.enroll.topic');
        Route::post('/student/finish-topic',    [SelectStudentController::class, 'finishTopic'])->name('v2.student.finish.topic');
        Route::post('/student/reset-testing-db', [SelectStudentController::class, 'resetTestingDatabase'])->name('v2.student.reset.testing.db');
        Route::post('/student/show-hint',       [SelectStudentController::class, 'showHint'])->name('v2.student.show.hint');
    });
});

// -----------------------------------------------
// TEACHER ROUTES
// -----------------------------------------------
Route::group(['middleware' => ['auth', 'teacher']], function () {
    Route::prefix('select')->group(function () {
        // Topics management
        Route::get('/teacher/materials',                        [SelectTeacherTopicsController::class, 'index'])->name('select_teacher');
        Route::get('/teacher/topics-table',                     [SelectTeacherTopicsController::class, 'topicsTable'])->name('v2.teacher.topics.table');
        Route::post('/teacher/topics/add',                      [SelectTeacherTopicsController::class, 'addTopicSubtopic'])->name('v2.teacher.topics.add');
        Route::get('/teacher/topics/{id}/edit',                 [SelectTeacherTopicsController::class, 'editTopicAjax']);
        Route::put('/teacher/topics/{id}',                      [SelectTeacherTopicsController::class, 'updateTopicAjax']);
        Route::delete('/teacher/topics/{id}/delete',            [SelectTeacherTopicsController::class, 'deleteTopic'])->name('v2.teacher.topics.delete');
        Route::delete('/teacher/subtopics/{id}/delete',         [SelectTeacherTopicsController::class, 'deleteSubtopic'])->name('v2.teacher.subtopics.delete');

        // Questions management
        Route::get('/teacher/questions/table',                  [SelectTeacherQuestionController::class, 'questionsTable'])->name('v2.teacher.questions.table');
        Route::post('/teacher/questions/save',                  [SelectTeacherQuestionController::class, 'saveQuestion'])->name('v2.teacher.questions.save');
        Route::delete('/teacher/questions/delete/{id}',         [SelectTeacherQuestionController::class, 'deleteQuestion']);
        // Route::get('/teacher/questions/list',                   [SelectTeacherQuestionController::class, 'getQuestionList']);
        // Route::get('/teacher/subtopics/list',                   [SelectTeacherQuestionController::class, 'getSubtopicList']);
        // Route::get('/teacher/topics/list',                      [SelectTeacherQuestionController::class, 'getTopicList']);

        // Submissions
        Route::get('/teacher/submissions',                      [SelectTeacherSubmissionController::class, 'index'])->name('v2.teacher.student.submissions');
    });
});
