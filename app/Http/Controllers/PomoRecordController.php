<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PomoRecordController extends Controller
{
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