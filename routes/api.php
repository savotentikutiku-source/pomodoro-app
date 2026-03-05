<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PomoRecordController; // ← これを追加
use App\Http\Controllers\AuthController; // ← これを追加

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 外部アプリ（Electron）専用の受付
Route::post('/records', [PomoRecordController::class, 'store']); // ← これを追加

// 新規登録の受付
Route::post('/register', [AuthController::class, 'register']); 

// ログインの受付 ← ★これを追加！
Route::post('/login', [AuthController::class, 'login']);