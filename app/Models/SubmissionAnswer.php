<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionAnswer extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'answer',
        'score',
    ];
}