<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PomoRecordController extends Controller
{
    // ★ここから追加！ 自分の記録を全部取り出す処理
    public function index(Request $request)
    {
        // 通行証（トークン）を持ってきたユーザーを特定
        $user = $request->user();

        // そのユーザーの記録だけを、新しい順（降順）で全部取得
        $records = PomoRecord::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 取得した記録をカレンダー（オランダ）へお返事
        return response()->json($records);
    }
    public function store(Request $request)
    {
        // 受け取ったデータを pomo_records テーブルに保存する
        DB::table('pomo_records')->insert([
            'task_name' => $request->task_name,
            'pomodoro_count' => 1, // 今回は1回分として記録
            'duration_minutes' => $request->duration_minutes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => '保存成功！']);
    }
}