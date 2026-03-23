<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pomodoro extends Model
{
    // ★ ここをデータベースの実際の項目と完全に一致させます！
    protected $fillable = ['category', 'count', 'date', 'color', 'hidden_from_list'];

    protected $casts = [
        'date' => 'date',
    ];
}