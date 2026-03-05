<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PomoRecord; // ★これが必要でした！
use Illuminate\Support\Facades\DB;

class PomoRecordController extends Controller
{
    // 自分の記録を全部取り出す処理（GET）
    public function index(Request $request)
    {
        // 通行証（トークン）を持ってきたユーザーを特定
        $user = $request->user();

        // そのユーザーの記録だけを、新しい順で取得
        $records = PomoRecord::where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->get();

        return response()->json($records);
    }

    // 自分の記録を保存する処理（POST）
    public function store(Request $request)
    {
        $user = $request->user(); // 誰が送ってきたか特定

        // データベースに保存
        $record = PomoRecord::create([
            'user_id' => $user->id, // ★誰のデータか記録！
            'task_name' => $request->task_name,
            'pomo_count' => $request->pomo_count ?? 1,
        ]);

        return response()->json($record);
    }
}