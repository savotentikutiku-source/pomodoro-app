<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\PomoRecordController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/pomodoro', function () {
    return view('pomodoro');
});

   // routes/web.php


// 画面を表示する道
Route::get('/pomodoro', [PomodoroController::class, 'index']);

// 保存ボタンを押した時の道
Route::post('/pomodoro', [PomodoroController::class, 'store'])->name('pomodoro.store');

// 削除用のルート（DELETEメソッド）
Route::delete('/pomodoro/{id}', [PomodoroController::class, 'destroy'])->name('pomodoro.destroy');

Route::post('/pomodoro/hide', [PomodoroController::class, 'hideCategory']);

// 管理ページの表示
Route::get('/pomodoro/manage', [PomodoroController::class, 'manage'])->name('pomodoro.manage');
// 除外処理
Route::post('/pomodoro/hide', [PomodoroController::class, 'hideCategory'])->name('pomodoro.hide');

// ★裏技：データベース強制再構築ルート
Route::get('/magic-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
    return 'データベースの再構築が完了しました！';
});