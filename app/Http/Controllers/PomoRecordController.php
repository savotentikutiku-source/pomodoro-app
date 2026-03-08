<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PomoRecord; // ← ★これがサーバーのパニックを直す魔法の一行です！
use Illuminate\Support\Facades\DB; // DB::rawを使うために必要です

class PomoRecordController extends Controller
{
    // 自分の記録を【合算して】取り出す処理（GET）
    public function index(Request $request)
    {
        $user = $request->user();

        // 同じ日・同じ項目のポモドーロ数を合算（SUM）して取得する魔法
        $records = PomoRecord::where('user_id', $user->id)
            ->select(
                'task_name',
                // フロントエンド側がカレンダー表示で 'created_at' を使っている可能性が高いため、名前を合わせて日付だけを切り出します
                DB::raw('DATE(created_at) as created_at'), 
                // ポモドーロ数を合計します
                DB::raw('SUM(pomo_count) as pomo_count')
            )
            ->groupBy('created_at', 'task_name') // 日付と項目名でまとめる
            ->orderBy('created_at', 'desc') // 新しい日付順に並べる
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