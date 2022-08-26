<?php

namespace App\Courses\Controllers;

use App\Courses\Helpers\ClientLRS;
use App\Courses\Helpers\LocalStatements;
use App\Courses\Models\Assignment;
use App\Courses\Services\CourseService;
use App\Courses\Services\StatementService;
use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Client\Response;

class StatementController extends Controller
{
    public function __construct(private CourseService $courseService, private StatementService $statementService)
    {
    }

    public function sendLaunchCourseStatement(int $courseId, int $sectionId) : Response
    {
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
        $course = $this->courseService->getCourse($courseId);
        $allCourseContent = json_decode($course->content);
        $section = $this->statementService->getSection($allCourseContent, $sectionId);

        /** @var User $user */
        $user = auth()->user();

        $statementLocalSend = new LocalStatements();
        $statementLocalSend->sendLocalStatement($user->user_id, $sectionId, 'passed');

//        $this->sendProgress($user->user_id, $courseId, $myCourseProgress['progress']);

        return ClientLRS::sendStatement($user, 'passed', $course, $section);
    }

//    public function sendProgress(int $userId, int $courseId, int $progress)
//    {
//        Assignment::where('course_id', $courseId)
//            ->where('student_id', $userId)
//            ->update(['progress' => $progress]);
//    }
}
