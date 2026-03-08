<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pomodoro;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PomodoroController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $calendarDates = collect();
        $date = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        while ($date <= $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY)) {
            $calendarDates->push($date->copy());
            $date->addDay();
        }

        // ★ここを修正：削除ボタンがパニックにならないよう「代表のID（MAX(id)）」を持たせます！
        $logs = Pomodoro::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('date', 'category', 'color', DB::raw('SUM(count) as count'), DB::raw('MAX(id) as id'))
            ->groupBy('date', 'category', 'color')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));

        // プルダウン用：表示フラグが立っているユニークな項目と色
        $categories = Pomodoro::where('hidden_from_list', false)
            ->select('category', 'color')
            ->get()
            ->unique('category');

        $monthTotals = Pomodoro::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('category', DB::raw('SUM(count) as total'), 'color')
            ->groupBy('category', 'color')
            ->get();

        return view('pomodoro.index', compact('logs', 'categories', 'calendarDates', 'startOfMonth', 'monthTotals'));
    }

    public function store(Request $request)
    {
        $today = now()->format('Y-m-d');
        // 選ばれた色（なければデフォルトの青）
        $color = $request->color ?? '#4f46e5';

        // 今日のデータを検索
        $record = Pomodoro::whereDate('date', $today)
            ->where('category', $request->category)
            ->first();

        // 合算または新規作成
        if ($record) {
            $record->increment('count', $request->count ?? 1);
            $record->color = $color; // 最新の色に上書き
            $record->save();
        } else {
            Pomodoro::create([
                'category' => $request->category,
                'count' => $request->count ?? 1,
                'color' => $color,
                'date' => $today,
            ]);
        }

        // 過去の同じ項目も、今回選んだ色にすべて自動で統一する
        Pomodoro::where('category', $request->category)->update(['color' => $color]);

        return back();
    }

    public function destroy($id)
    {
        Pomodoro::findOrFail($id)->delete();
        return back();
    }

    public function manage()
    {
        $categories = Pomodoro::select('category', 'hidden_from_list', 'color')->get()->unique('category');
        return view('pomodoro.manage', compact('categories'));
    }

    public function hideCategory(Request $request)
    {
        $current = Pomodoro::where('category', $request->category)->first()->hidden_from_list;
        Pomodoro::where('category', $request->category)->update(['hidden_from_list' => !$current]);
        return back();
    }
}