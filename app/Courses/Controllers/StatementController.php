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

    public function sendLaunchCourseStatement(Request $request, int $courseId, int $sectionId) : Response|string
    {
        $myCourseProgressLaunched = $request->input('myCourseProgressLaunched');

        if (in_array($sectionId,$myCourseProgressLaunched)){
            return "Already sent launch";
        }

        $course = $this->courseService->getCourse($courseId);
        $allCourseContent = json_decode($course->content);
        $section = $this->statementService->getSection($allCourseContent, $sectionId);

        /** @var User $user */
        $user = auth()->user();

        $statementLocalSend = new LocalStatements();
        $statementLocalSend->sendLocalStatement($user->user_id, $sectionId, 'launched');

        return ClientLRS::sendStatement($user, 'launched', $course, $section);
    }

    public function sendPassCourseStatement(Request $request, int $courseId, int $sectionId) : Response|string
    {
        $myCourseProgressPassed = $request->input('myCourseProgressPassed');

        if (in_array($sectionId,$myCourseProgressPassed)){
            return "Already sent pass";
        }

        $course = $this->courseService->getCourse($courseId);
        $allCourseContent = json_decode($course->content);
        $section = $this->statementService->getSection($allCourseContent, $sectionId);

        /** @var User $user */
        $user = auth()->user();

        $statementLocalSend = new LocalStatements();
        $statementLocalSend->sendLocalStatement($user->user_id, $sectionId, 'passed');

        return ClientLRS::sendStatement($user, 'passed', $course, $section);
    }

    public function getCourseStatements(int $courseId) : array
    {
        return ClientLRS::getCoursesStatements([$courseId]);
    }

    public function sendPassedCourseStatements(Request $request, int $courseId) : Response
    {
        /** @var User $user */
        $user = auth()->user();
        $course = $this->courseService->getCourse($courseId);
        return ClientLRS::sendStatement($user, ClientLRS::PASSED, $course);
    }

}
