<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pomodoro extends Model
{
    protected $fillable = ['category', 'count', 'date'];

    protected $casts = [
        'date' => 'date',
    ];
}


