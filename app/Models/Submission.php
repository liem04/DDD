<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'score',
        'status',
        'submitted_at',
    ];
}