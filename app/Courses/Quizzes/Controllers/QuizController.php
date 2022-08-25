<?php

namespace App\Courses\Quizzes\Controllers;


use App\Courses\Quizzes\Models\Quiz;
use App\Courses\Services\QuizService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class QuizController extends Controller
{
    public function play(int $id){
        return view('pages.courses.quizzes.play', ['quiz' => $id]);
    }

    public function retrieveQuiz(int $id)
    {
        $quiz = (new QuizService())->getQuiz($id, ['questions.options']);
        $resp = [];

        $quiz->questions->each(function ($question) use (&$resp) {
            $options = [];
            $question->options->each(function ($option) use (&$options) {
                $options[] = (object)['optionBody' => $option->option_body, 'isCorrect' => $option->is_correct];
            });

            if (!empty($options)) {
                $resp[] = (object)['question' => $question->question_body, 'options' => (object)$options];
            }
        });

        return response()->json($resp);
    }

    public function storeResults(Request $request, int $id)
    {
        $correctAnswersCount = $request->json()->get('correctAnswersCount');
        $quizService = new QuizService();
        $quiz = $quizService->getQuiz($id, ['questions']);
        $quizService->storeResults($quiz, $correctAnswersCount);

        return response('You answers were stored', 302);
    }

    public function showResults(int $id)
    {
        return view('pages.courses.quizzes.results', ['quiz' => $id]);
    }

    public function retrieveResults(int $id)
    {
        $quizService = new QuizService();
        $quiz = $quizService->getQuiz($id);
        $results = $quizService->retrieveResults($quiz);
        return response()->json((object)$results, 200);
    }

    public function showQuestions(int $id)
    {
        $questions = (new QuizService())->getQuiz($id, ['questions'])->questions;
        return view('pages.courses.quizzes.questions', ['questions' => $questions, 'quiz' => $id]);
    }

    public function storeQuestions(Request $request, int $id)
    {
        $questions = $request->json()->all();
        $quizService = new QuizService();
        $quiz = $quizService->getQuiz($id);

        foreach($questions as $question) {
            $quizService->createQuestion($quiz, $question);
        }

        return response('', 302);
    }

    public function showOptions(int $quizId, int $questionId)
    {
        $options = (new QuizService())
            ->getQuiz($quizId, ['questions.options'])
            ->questions
            ->where('question_id', $questionId)
            ->first()
            ->options;

        return view('pages.courses.quizzes.options', ['options' => $options, 'quiz' => $quizId, 'question' => $questionId]);
    }

    public function storeOptions(Request $request, int $quizId, int $questionId)
    {
        $options = $request->json()->all();
        $quizService = new QuizService();

        if (!($quizService->isAnswerSelected($options))) {
            return response('Answer for this question wasn\'t selected', 200);
        }

        $quiz = $quizService->getQuiz($quizId, ['questions']);
        $quizService->deleteAllQuestionOptions($quiz, $questionId);

        foreach($options as $option) {
            $quizService->addQuestionOption($questionId, $option);
        }

        return response('Question options edited successfully!', 200);
    }

    public function deleteQuestion(Request $request, int $id)
    {
        $questionId = $request->json()->get('questionId');
        $quizService = new QuizService();
        $quiz = $quizService->getQuiz($id, ['questions']);
        $quizService->deleteAllQuestionOptions($quiz, $questionId);
        $quizService->deleteQuestion($quiz, $questionId);

        return response('Question deleted successfully!', 200);
    }
}
