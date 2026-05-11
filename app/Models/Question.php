<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'type',
        'content',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'payload',
        'score',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}