<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Pomodoro Tracker</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f0f2f5;
            margin: 20px;
            color: #000;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
            height: 110px;
            overflow: hidden;
        }

        .sun {
            color: #e53e3e;
            background: #fff5f5;
        }

        .sat {
            color: #3182ce;
            background: #ebf8ff;
        }

        /* ★バッジのデザイン：背景色＋白文字（影付きで読みやすく） */
        .log-badge {
            font-size: 11px;
            margin-top: 4px;
            padding: 4px 6px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .log-text {
            flex: 1;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-del {
            border: none;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            cursor: pointer;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            margin-left: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 style="text-align: center;">{{ $startOfMonth->format('Y年m月') }} の集計</h2>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
            @foreach($monthTotals as $t)
                <div
                    style="padding: 4px 12px; border-radius: 15px; background: white; border: 1px solid #ddd; border-left: 5px solid {{ $t->color }}">
                    {{ $t->total }} : {{ $t->category }}
                </div>
            @endforeach
        </div>

        <form action="/pomodoro" method="POST"
            style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: flex; gap: 10px;">
            @csrf
            <select id="sel" onchange="updateForm(this)">
                <option value="" data-color="#4f46e5">-- 過去から選ぶ --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->category }}" data-color="{{ $cat->color }}">{{ $cat->category }}</option>
                @endforeach
            </select>
            <input type="text" id="new_cat" name="category" placeholder="項目名" required style="flex:1;">
            <input type="color" id="clr" name="color" value="#4f46e5"
                style="width: 40px; border: none; cursor: pointer;">
            <input type="number" name="count" value="1" min="1" style="width: 50px;">
            <button type="submit"
                style="padding: 5px 15px; background: #4f46e5; color: white; border: none; border-radius: 5px;">記録</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th class="sun">日</th>
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th class="sat">土</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calendarDates->chunk(7) as $week)
                    <tr>
                        @foreach($week as $idx => $date)
                            <td class="{{ $idx == 0 ? 'sun' : ($idx == 6 ? 'sat' : '') }}"
                                style="{{ $date->month != $startOfMonth->month ? 'opacity: 0.2;' : '' }}">
                                <strong>{{ $date->day }}</strong>
                                @if(isset($logs[$date->format('Y-m-d')]))
                                    @foreach($logs[$date->format('Y-m-d')] as $log)
                                        <div class="log-badge" style="background-color: {{ $log->color ?? '#4f46e5' }};">
                                            <div class="log-text">{{ $log->count }} : {{ $log->category }}</div>
                                            <form action="{{ route('pomodoro.destroy', $log->id) }}" method="POST" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-del">×</button>
                                            </form>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="text-align: right; margin-top: 15px;"><a href="{{ route('pomodoro.manage') }}"
                style="color:#666; text-decoration:none;">⚙ リスト整理</a></div>
    </div>

    <script>
        // --- ① もともとあったプルダウン用の関数 ---
        function updateForm(s) {
            const i = document.getElementById('new_cat');
            const p = document.getElementById('clr');
            const o = s.options[s.selectedIndex];
            i.value = s.value;
            if (o.dataset.color && o.dataset.color.length === 7) {
                p.value = o.dataset.color;
            }
        }

        // --- ② ★ここから追加：ページを開いた瞬間にデータを取ってくる魔法 ---
        
        // 1. あなたの通行証（トークン）をここに貼り付け
        const myToken = "7|xwNcaN19YvDSnoWMv4SLHBiHgv7E0UC6pCG9pdVk6c26d11b"; 

        // 2. 日本のRailwayサーバーへのお願い
        const recordUrl = 'https://pomodoro-app-production-2e96.up.railway.app/api/records';

        console.log("通信開始..."); // 開発者ツールで進捗が見えるようにします

        fetch(recordUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${myToken}`
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('通信エラーです');
            return response.json();
        })
        .then(data => {
            console.log("🚀 クラウドから届いたデータ:", data);
            if(data.length > 0) {
                alert(`大成功！！！クラウドから ${data.length} 件の記録を無事に受信しました！\n\n（開発者ツールのConsoleタブを見てください）`);
            } else {
                alert("通信成功！でも、まだデータが0件みたいです。");
            }
        })
        .catch(error => {
            console.error("エラー詳細:", error);
            alert("データの取得に失敗しました。トークンが正しいか確認してください。");
        });
    </script>
</body>

</html>