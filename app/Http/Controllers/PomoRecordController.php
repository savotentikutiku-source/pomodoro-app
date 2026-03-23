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
        // タイムゾーンのズレを防ぐため、日本時間を明示的に指定
        $today = now()->timezone('Asia/Tokyo')->format('Y-m-d'); 
        
        $category = $request->task_name;
        $count = $request->pomo_count ?? 1;

        // ★ user_id は使わずに検索します！
        $record = Pomodoro::whereDate('date', $today)
            ->where('category', $category)
            ->first();

        if ($record) {
            $record->increment('count', $count);
        } else {
            Pomodoro::create([
                // ★ user_id はテーブルに無いので消しました！
                'category' => $category,
                'count' => $count,
                'color' => '#4f46e5',
                'date' => $today,
            ]);
        }

        return response()->json(['message' => 'カレンダーへの保存が大成功しました！']);
    }
}