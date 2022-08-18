<?php

namespace App\Courses\Controllers;

use App\Courses\Helpers\ClientLRS;
use App\Courses\Services\CourseService;


use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;


class StatementController extends Controller
{
    public function __construct(private CourseService $courseService)
    {

    }

    public function sendLaunchCourseStatement(Request $request, int $courseId, int $sectionId) : Response
    {
        $course = $this->courseService->getCourse($courseId);
        $course->content = json_decode($course->content);
        $section = [];

        foreach ($course->content as $element) {
            if ($element->section_id == $sectionId) {
                $section = $element;
                break;
            }
        }

        /** @var User $user */
        $user = auth()->user();

        $statement = ClientLRS::compileStatement($user, 'launched', $course, $section);

        return ClientLRS::sendStatement($statement);
    }

    public function sendPassCourseStatement(Request $request, int $courseId, int $sectionId) : Response
    {
        $course = $this->courseService->getCourse($courseId);
        $course->content = json_decode($course->content);
        $section = [];

        foreach ($course->content as $element) {
            if ($element->section_id == $sectionId) {
                $section = $element;
                break;
            }
        }

        /** @var User $user */
        $user = auth()->user();

        $statement = ClientLRS::compileStatement($user, 'passed', $course, $section);

        return ClientLRS::sendStatement($statement);
    }

    public function getMyCourseProgress()
    {
        /** @var User $user */
        $user = auth()->user();
        $response = ClientLRS::getStatements($user->name);
        $statements = json_decode($response->body(), true)["body"];

        dd($statements);

//        foreach($statements as $statement) {
//            echo $statement['id'];
//            $statement['content'] = json_decode($statement['content']);
//            echo "<pre>" . json_encode($statement['content'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";
//        }
        return $statements;
    }

}
