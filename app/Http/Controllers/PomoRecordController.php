<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PomoRecord; // ← ★これがサーバーのパニックを直す魔法の一行です！
use Illuminate\Support\Facades\DB;

class PomoRecordController extends Controller
{
    // 自分の記録を全部取り出す処理（GET）
    public function index(Request $request)
    {
        $user = $request->user();

        // データベースから、このユーザーの記録だけを新しい順で取ってくる
        $records = PomoRecord::where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->get();

        return response()->json($records);
    }

    // 自分の記録を保存する処理（POST）
    public function store(Request $request)
    {
        $user = $request->user();

        $record = PomoRecord::create([
            'user_id' => $user->id,
            'task_name' => $request->task_name,
            'pomo_count' => $request->pomo_count ?? 1,
        ]);

        return response()->json($record);
    }
}