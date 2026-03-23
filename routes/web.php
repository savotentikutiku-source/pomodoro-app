<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PomodoroController;

// 1. トップページにアクセスしたら、自動でカレンダーへワープさせる便利な設定！
Route::get('/', function () {
    return redirect('/pomodoro');
});

// 2. カレンダー画面を表示
Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro.index');

// 3. 記録を保存
Route::post('/pomodoro', [PomodoroController::class, 'store'])->name('pomodoro.store');

// 4. 記録を削除
Route::delete('/pomodoro/{id}', [PomodoroController::class, 'destroy'])->name('pomodoro.destroy');

// 5. リスト整理（管理）ページを表示
Route::get('/pomodoro/manage', [PomodoroController::class, 'manage'])->name('pomodoro.manage');

// 6. リストの表示・非表示を切り替え
Route::post('/pomodoro/hide', [PomodoroController::class, 'hideCategory'])->name('pomodoro.hide');

// ★ 裏技：データベース強制再構築
Route::get('/magic-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
    return 'データベースの再構築が完了しました！';
});