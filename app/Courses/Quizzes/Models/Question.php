<?php

namespace App\Courses\Quizzes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $primaryKey = 'question_id';

    protected $guarded = [];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'question_id', 'question_id');
    }

    public function answer()
    {
        return $this->hasOne(Answer::class, 'question_id', 'question_id');
    }

    public function id()
    {
        return $this->question_id;
    }
}
