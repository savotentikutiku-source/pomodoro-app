<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PomoRecord extends Model
{
    use HasFactory;

    // ★ここ！ここに 'user_id' や 'pomo_count' は入っていますか？
    protected $fillable = [
        'user_id',
        'task_name',
        'pomo_count',
    ];
}