<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// ★ここが一番の魔法！カレンダーと同じ「Pomodoro」の箱を使うように変更します
use App\Models\Pomodoro; 

class PomoRecordController extends Controller
{
    // 自分の記録を取り出す処理（今回は使いませんが残しておきます）
    public function index(Request $request)
    {
        // 既存のコードのままでOKです
        return response()->json(['message' => 'This is API index']);
    }

    // ★デスクトップアプリからデータを受け取って保存する処理
    public function store(Request $request)
    {
        $today = now()->format('Y-m-d');
        // タイマーから送られてきた「task_name」を、カレンダー用の「category」として扱う
        $category = $request->task_name;
        $count = $request->pomo_count ?? 1;

        // カレンダーと同じ箱（Pomodoro）の、今日のデータを検索
        $record = Pomodoro::whereDate('date', $today)
            ->where('category', $category)
            ->first();

        if ($record) {
            // すでに今日の同じ項目（プログラミング等）があれば、送られてきた回数を足し算
            $record->increment('count', $count);
        } else {
            // なければカレンダー用の新しいデータとして作成
            Pomodoro::create([
                'category' => $category,
                'count' => $count,
                'color' => '#4f46e5', // カレンダーでの標準色（青）を設定
                'date' => $today,
            ]);
        }

        return response()->json(['message' => 'カレンダーへの保存が大成功しました！']);
    }
}