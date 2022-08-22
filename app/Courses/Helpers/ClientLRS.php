<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Course;
use App\Users\Models\User;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ClientLRS
{

    public static function compileStatement(User $user, string $verb, Course $course, mixed $section = null): bool|string
    {
        if ($section) {
            return ClientLRS::compileSectionStatement($user, $verb, $course, $section);
        } else {
            return ClientLRS::compileCourseStatement($user, $verb, $course);
        }
    }

    protected static function compileSectionStatement(User $user, string $verb, Course $course, mixed $section = null): bool|string
    {
        $sectionStatement = [
            "actor" => [
                "objectType" => "Agent",
                "mbox" => "mailto:" . $user->email,
                "openid" => "http://course-zone.org/expapi/users:" . $user->user_id,
                "name" => "http://course-zone.org/expapi/users:" . $user->name,
            ],
            "verb" => [
                "id" => "http://course-zone.org/expapi/verbs:" . $verb,
                "display" => [
                    "en-US" => $verb,
                ]
            ],
            "object" => [
                "id" => "http://course-zone.org/expapi/courses/section:" . $section->section_id,
                "type" => "http://course-zone.org/expapi/courses/section/type:" . $section->type,
                "display" => [
                    "en-US" => $section->title,
                ],
                'objectType' => 'Activity',
            ],
            'context' => [
                'id' => 'http://course-zone.org/expapi/courses:' . $course->course_id,
                "display" => [
                    "en-US" => $course->title,
                ],
            ]
        ];
        return json_encode($sectionStatement);
    }

    protected static function compileCourseStatement(User $user, string $verb, Course $course, mixed $section = null): bool|string
    {
        $courseStatement = [
            "actor" => [
                "objectType" => "Agent",
                "mbox" => "mailto:" . $user->email,
                "openid" => "http://course-zone.org/expapi/users:" . $user->user_id,
                "name" => "http://course-zone.org/expapi/users:" . $user->name,
            ],
            "verb" => [
                "id" => "http://course-zone.org/expapi/verbs:" . $verb,
                "display" => [
                    "en-US" => $verb,
                    "ru-Ru" => "запустить",
                ]
            ],
            "object" => [
                "id" => 'http://course-zone.org/expapi/courses:' . $course->course_id,
                "display" => [
                    "en-US" => $course->title,
                ],
                'objectType' => 'Activity',
            ],
        ];
        return json_encode($courseStatement);
    }

    protected static function compileFilters(string $actor = "", string $verb = "", string $object = "", string $context = ""): array
    {
        $compileFilters = [];
        if ($verb) {
            $compileFilters['verb-filter'] = "http://course-zone.org/expapi/verbs:" . $verb;
        }
        if ($actor) {
            $compileFilters['actor-filter'] = "mailto:" . $actor;
        }

        if ($context) {
            $compileFilters['context-filter'] = 'http://course-zone.org/expapi/courses:' . $context;
            if ($object) {
                $compileFilters['object-filter'] = "http://course-zone.org/expapi/courses/section:" . $object;
            }
        } elseif ($object) {
            $compileFilters['object-filter'] = "http://course-zone.org/expapi/courses:" . $object;
        }
        return $compileFilters;
    }

    protected static function getStaticByVerbFromStatement(array $statements, string $verb): array
    {
        $staticVerb = [];

        foreach($statements as $statement)
        {
            $content = json_decode($statement->content);
            $object = explode(':', $content->object->id);

            $sectionId = end($object);
            if (!in_array($sectionId, $staticVerb))
            {
                $staticVerb[] = $sectionId;
            }
        }

        return $staticVerb;
    }

    public static function sendStatement($statement): Response
    {
        $tokenLRS = config('services.lrs.token');

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->withBody($statement, 'application/json')
            ->post('http://127.0.0.1:8001/api/statements');
        return $response;
    }

    public static function getStatements(string $userMail = "", string $verb = "", string $object = "", string $context = ""): Response
    {
        $filters = ClientLRS::compileFilters(actor: $userMail, verb: $verb, object: $object, context: $context);
        $tokenLRS = config('services.lrs.token');

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->get('http://127.0.0.1:8001/api/statements', $filters);

        return $response;
    }

    public static function getProgressStudent(string $userMail, int $courseId) : array
    {
        $progressSections = [];

        $requestPassed = ClientLRS::getStatements(userMail: $userMail, verb: 'passed', context: $courseId);
        $requestLaunched = ClientLRS::getStatements(userMail: $userMail, verb: 'launched', context: $courseId);

        $bodyPassedRequest = json_decode($requestPassed->body())->body;
        $bodyLaunchedRequest = json_decode($requestLaunched->body())->body;

        $progressSections['passed'] = ClientLRS::getStaticByVerbFromStatement($bodyPassedRequest, 'passed');
        $progressSections['launched'] = ClientLRS::getStaticByVerbFromStatement($bodyLaunchedRequest, 'launched');

        return $progressSections;
    }
}
