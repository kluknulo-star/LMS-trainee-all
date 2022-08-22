<?php

namespace App\Courses\Helpers;


use App\Courses\Models\Course;
use App\Users\Models\User;

class StatementsHelper
{

    //done
    public static function compileStatement(User $user, string $verb, Course $course, mixed $section = null): bool|string
    {
        $baseStatement = [
            "actor" => [
                "objectType" => "Agent",
                "mbox" => "mailto:" . $user->email,
                "name" => "http://course-zone.org/expapi/users/" . $user->name,
                "display" => [
                    "en-US" => $user->name,
                ]
            ],
            "verb" => [
                "id" => "http://adlnet.gov/expapi/verbs/" . $verb,
                "display" => [
                    "en-US" => $verb,
                ]
            ],
        ];

        $tailStatement = [
            "object" => [
                "id" => 'http://course-zone.org/expapi/courses/' . $course->course_id,
                "display" => [
                    "en-US" => $course->title,
                ],
                'objectType' => 'Activity',
            ],
        ];

        if ($section) {
            $tailStatement = [
                "object" => [
                    "id" => "http://course-zone.org/expapi/courses/section/" . $section->section_id,
                    "type" => "http://course-zone.org/expapi/courses/section/type/" . $section->type,
                    "display" => [
                        "en-US" => $section->title,
                    ],
                    'objectType' => 'Activity',
                ],
                'context' => [
                    'id' => 'http://course-zone.org/expapi/courses/' . $course->course_id,
                    "display" => [
                        "en-US" => $course->title,
                    ],
                ]
            ];
        }

        $compiledStatement = json_encode(array_merge($baseStatement, $tailStatement));

        return $compiledStatement;
    }

    //done
    public static function compileFilters(string $actor = "", string $verb = "", string $object = "", string $context = ""): array
    {
        $compileFilters = [];
        if ($verb) {
            $compileFilters['verb-filter'] = "http://course-zone.org/expapi/verbs/" . $verb;
        }
        if ($actor) {
            $compileFilters['actor-filter'] = "mailto:" . $actor;
        }

        if ($context){
            $compileFilters['context-filter'] = 'http://course-zone.org/expapi/courses/' . $context;
        }

        if ($object) {
            $compileFilters['object-filter'] = "http://course-zone.org/expapi/courses/" . $object;
        }

        if ($context && $object) {
            $compileFilters['object-filter'] = "http://course-zone.org/expapi/courses/section/" . $object;
        }

        return $compileFilters;
    }

    public static function getStaticByVerbFromStatement(array $statements, string $verb): array
    {
        $staticVerb = [];

        foreach ($statements as $statement) {
            $content = json_decode($statement->content);
            $object = explode(':', $content->object->id);

            $sectionId = end($object);
            if (!in_array($sectionId, $staticVerb)) {
                $staticVerb[] = $sectionId;
            }
        }

        return $staticVerb;
    }

}
