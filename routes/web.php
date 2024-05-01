<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ChallengeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ログイン時にResources/Pages/Welcome.vueを表示
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ログイン時のルーティング
Route::middleware('auth')->group(function () {
    // マイページ画面表示
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');

    // プロフィール画面表示
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // プロフィール情報更新
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // ユーザ削除
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // プロジェクト作成画面
    Route::get('/project/create', [ProjectController::class, 'create'])->name('project.create');
    // プロジェクト作成
    Route::post('/project/create', [ProjectController::class, 'store'])->name('project.store');

    // プロジェクト詳細画面
    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project.show');
    // Step詳細画面
    Route::get('/project/{project_id}/step/{step_id}', [ProjectController::class, 'showDetail'])->name('step.show');

    // プロジェクト編集画面(作成者のみ)
    Route::get('/project/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
    // プロジェクト編集(作成者のみ)
    Route::put('/project/edit/{id}', [ProjectController::class, 'update'])->name('project.update');
    // プロジェクト編集(作成者のみ)
    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

    Route::post('/challenges/start', [ChallengeController::class, 'start'])->name('challenges.start');
    Route::post('/challenges/stop', [ChallengeController::class, 'stop'])->name('challenges.stop');

    Route::post('/challenges/toggleStatus', [ChallengeController::class, 'toggleStatus'])->name('challenges.toggleStatus');
});

// プロジェクト一覧画面(非ログインユーザも閲覧可能)
Route::get('/project', [ProjectController::class, 'index'])->name('project.index');

require __DIR__.'/auth.php';
