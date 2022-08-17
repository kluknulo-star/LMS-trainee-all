<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Course;
use App\Users\Models\User;

//use http\Client\Response;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Psy\Util\Json;

class ClientLRS
{

    public static function compileStatement(User $user,string  $verb,Course $course,mixed $section = null): bool|string
    {
        if ($section) {
            return ClientLRS::compileSectionStatement($user,$verb,$course,$section);
        }
        else
        {
            return ClientLRS::compileCourseStatement($user,$verb,$course);
        }
    }

    protected static function compileSectionStatement(User $user,string  $verb,Course $course,mixed $section = null): bool|string
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
//                    "ru-Ru" => "запустить",
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


    protected static function compileCourseStatement(User $user,string  $verb,Course $course,mixed $section = null): bool|string
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

    /**
     * @return \Illuminate\Http\Client\Response
     */
    public static function sendStatement($statement) : Response
    {
        $tokenLRS = env('LRS_TOKEN', null);

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->withBody($statement, 'application/json')
            ->post('http://127.0.0.1:8001/api/statements');
        return $response;
    }


    public static function getStatements(string $filter = 'Anatoliy'): Response
    {
        $tokenLRS = env('LRS_TOKEN', null);
        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->get('http://127.0.0.1:8001/api/statements', [
            'actor-filter' => "http://course-zone.org/expapi/users:" . $filter,
//        'verb-filter' => VERB_PREFIX . 'confirm',
        ]);
        return $response;
    }

}
