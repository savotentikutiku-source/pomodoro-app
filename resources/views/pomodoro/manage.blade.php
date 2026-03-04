<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>リスト管理 - Pomodoro Track</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .btn-hide {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>プルダウン項目の整理</h2>
        @if(session('message'))
            <p style="color: #4f46e5; font-weight: bold; background: #eef2ff; padding: 10px; border-radius: 5px;">
                {{ session('message') }}</p>
        @endif

        @foreach($categories as $cat)
            <div class="category-item"
                style="display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee; align-items: center;">
                <span style="{{ $cat->hidden_from_list ? 'color: #ccc; text-decoration: line-through;' : '' }}">
                    {{ $cat->category }}
                </span>
                <form action="{{ route('pomodoro.hide') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category" value="{{ $cat->category }}">
                    @if($cat->hidden_from_list)
                        <button type="submit"
                            style="background-color: #10b981; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">再表示する</button>
                    @else
                        <button type="submit"
                            style="background-color: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">リストから消す</button>
                    @endif
                </form>
            </div>
        @endforeach

        <a href="/pomodoro"
            style="display: block; text-align: center; margin-top: 20px; color: #4f46e5; text-decoration: none;">←
            カレンダーに戻る</a>
    </div>
</body>

</html>