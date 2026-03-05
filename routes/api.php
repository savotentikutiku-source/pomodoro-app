<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PomoRecordController;
use App\Http\Controllers\AuthController;

// ==========================================
// 誰でも通れる道（通行証不要）
// ==========================================

// 新規登録の受付
Route::post('/register', [AuthController::class, 'register']); 

// ログインの受付
Route::post('/login', [AuthController::class, 'login']);


// ==========================================
// ★通行証（トークン）が必要な特別な道（関所）
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // 【元からある】ユーザー情報確認用
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 【今回新しく追加！】カレンダー表示用（記録を全部ちょうだい）
    Route::get('/records', [PomoRecordController::class, 'index']);
    
    // 【外から移動してきた！】タイマーからの送信（記録を保存して）
    Route::post('/records', [PomoRecordController::class, 'store']);
    
});