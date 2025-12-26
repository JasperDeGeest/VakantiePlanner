<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'class',
        'start',
        'end',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];
}
