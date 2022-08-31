<?php

namespace App\Courses\Controllers;

use App\Courses\Helpers\ClientLRS;
use App\Courses\Helpers\LocalStatements;
use App\Courses\Services\CourseService;
use App\Courses\Services\StatementService;
use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function __construct(private CourseService $courseService, private StatementService $statementService)
    {
    }

    public function sendLaunchCourseStatement(int $courseId, int $sectionId) : Response
    {
        // myCourseProgressLaunched - массив с launched content stmts
        // myCourseProgressPassed - массив с passed content stmts

        $course = $this->courseService->getCourse($courseId);
        $allCourseContent = json_decode($course->content);
        $section = $this->statementService->getSection($allCourseContent, $sectionId);

        /** @var User $user */
        $user = auth()->user();

        $statementLocalSend = new LocalStatements();
        $statementLocalSend->sendLocalStatement($user->user_id, $sectionId, 'launched');

        return ClientLRS::sendStatement($user, 'launched', $course, $section);
    }

    public function sendPassCourseStatement(int $courseId, int $sectionId) : Response
    {
        // myCourseProgressLaunched - массив с launched content stmts
        // myCourseProgressPassed - массив с passed content stmts

        $course = $this->courseService->getCourse($courseId);
        $allCourseContent = json_decode($course->content);
        $section = $this->statementService->getSection($allCourseContent, $sectionId);

        /** @var User $user */
        $user = auth()->user();

        $statementLocalSend = new LocalStatements();
        $statementLocalSend->sendLocalStatement($user->user_id, $sectionId, 'passed');
        $statementLocalSend->setProgress($user->user_id, $courseId, count($course->content->where('deleted_at', null)));

        return ClientLRS::sendStatement($user, 'passed', $course, $section);
    }

    public function getCourseStatements(int $courseId) : array
    {
        return ClientLRS::getCoursesStatements([$courseId]);
    }

    public function sendPassedCourseStatements(Request $request, int $courseId)
    {
        // myCourseProgressLaunched - массив с launched content stmts
        // myCourseProgressPassed - массив с passed content stmts

        /** @var User $user */
        $user = auth()->user();
        $course = $this->courseService->getCourse($courseId);
        return ClientLRS::sendStatement($user, ClientLRS::PASSED, $course);
    }

}
