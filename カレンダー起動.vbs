Set ws = CreateObject("WScript.Shell")

' 1. 黒い画面を出さずに（0）、裏側でサーバーを起動！
ws.Run "cmd /c cd C:\laravel\asobi\pomodoro && php artisan serve", 0, False

' 2. サーバーが起き上がるまで、念のため2秒（2000ミリ秒）だけ待つ
WScript.Sleep 2000

' 3. ブラウザでカレンダーの画面を開く！
ws.Run "http://localhost:8000/pomodoro"