<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardView;
use App\Http\Controllers\DataController;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\SocialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/login', [AuthController::class, 'proses'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('post_signup');
Route::get('/auth/redirect', action: [AuthController::class, 'redirect'])->name(name: 'google.redirect');
Route::get('/google/redirect', action: [AuthController::class, 'googleCallback'])->name(name: 'google.callback');

Route::get('/make', function () {
    echo Hash::make("123");
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/', [DataController::class, 'index'])->middleware('guest');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/logoutt', [AuthController::class, 'logoutt'])
    ->name('logoutt');
Route::get('/logout', [AuthController::class, 'logoutt'])
    ->name('logout');

// Route::group(["prefix" => 'test', 'middleware' => ['login'],  'as' => 'test.'], function(){


Route::get('/dashboard-student', [DashboardView::class, 'index'])
    ->name('dashboard-student')
    ->middleware('auth');

Route::get('/dashboard_teacher', [DashboardView::class, 'index'])
    ->name('dashboard-teacher')
    ->middleware('auth');

Route::get('/learning-student', function () {
    return view('learning_student');
})->name('learning_student');

Route::get('/material-detail', function () {
    return view('material_detail');
})->name('material_detail');

// Route Node JS
// require __DIR__ . '/nodejs_routes.php';

// Route Aplas

// Route Flutter

// Route
require __DIR__ . '/android23_routes.php';

require __DIR__ . '/php_routes.php';
require __DIR__ . '/phpunit_routes.php';
require __DIR__ . '/react_routes.php';
require __DIR__ . '/mysql_routes.php';
require __DIR__ . '/flutter_routes.php';
require __DIR__ . '/select_routes.php';
// require __DIR__ . '/python_routes.php';
// });
