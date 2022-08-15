<?php

namespace App\Courses\Services;

use App\Courses\Quizzes\Models\Answer;
use App\Courses\Quizzes\Models\Option;
use App\Courses\Quizzes\Models\Question;
use App\Courses\Quizzes\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;

class QuizService
{
    public function __construct(private Quiz $quiz)
    {
    }

    public function getQuestions() :  Collection|null
    {
        return $this->quiz->questions()->get();
    }

    public function getQuestion(int $id) : Question|null
    {
        return $this->getQuestions()->where('question_id', $id)->first();
    }

    public function deleteQuestion(int $id) : void
    {
        Question::where('question_id', $id)->delete();
    }

    public function addQuestion(string $question) : void
    {
        Question::create([
            'question_body' => $question,
            'quiz_id' => $this->quiz->id(),
        ]);
    }

    public function getQuestionAnswer(int $id) : Answer|null
    {
       return $this->getQuestion($id)->answer()->first();
    }

    public function addQuestionAnswer(int $id, string $answerBody) : void
    {
        $this->getQuestion($id)->answer()->updateOrCreate(
            ['question_id' => $id],
            ['answer_body' => $answerBody]
        );
    }

    public function getQuestionOptions(int $id) :  Collection|null
    {
        return $this->getQuestion($id)->options()->get();
    }

    public function addQuestionOption(int $id, string $option) : void
    {
        Option::create([
            'option_body' => $option,
            'question_id' => $id,
        ]);
    }

    public function deleteQuestionOption(int $questionId, int $optionId) : void
    {
        Option::where([
            'question_id', '=', $questionId,
            'option_id', '=', $optionId,
        ])->delete();
    }

}
