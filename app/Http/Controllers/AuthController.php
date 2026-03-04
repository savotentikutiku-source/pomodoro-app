<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 新規登録の受付処理
    public function register(Request $request)
    {
        // 1. 送られてきたデータ（名前、メアド、パスワード）に空っぽがないかチェック
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // 2. データベース（usersテーブル）に新しいユーザーを作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // パスワードは必ず暗号化！
        ]);

        // 3. このユーザー専用の「電子通行証（トークン）」を発行
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. 通行証をElectron（オランダ）へ返す
        return response()->json([
            'message' => '登録成功！',
            'access_token' => $token,
        ]);
    }
}