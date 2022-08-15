<?php

namespace App\Courses\Quizzes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Option extends Model
{
    use HasFactory;

    protected $table = 'quiz_options';

    protected $primaryKey = 'option_id';

    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
