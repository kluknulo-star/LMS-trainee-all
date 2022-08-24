<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Eloquent\Model;

class StatementHelper
{
    /**
     * Simple builder for xAPI statements
     *
     * @return bool|string $compiledStatement
     */
    public static function compileStatement(User $user, string $verb, Model $course, mixed $section = null): bool|string
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
                    "id" => "http://course-zone.org/expapi/courses/section/" . $section->item_id,
                    "type" => "http://course-zone.org/expapi/courses/section/type/" . $section->type_id,
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

    /**
     * Simple filter builder for xAPI statements
     *
     * @return array $compiledStatement
     */
    public static function compileFilters(string $actor = "", string $verb = "", string $object = "", string $context = ""): array
    {
        $compiledFilters = [];
        if ($verb) {
            $compiledFilters['verb-filter'] = "http://adlnet.gov/expapi/verbs/" . $verb;
        }

        if ($actor) {
            $compiledFilters['actor-filter'] = "mailto:" . $actor;
        }

        if ($context){
            $compiledFilters['context-filter'] = 'http://course-zone.org/expapi/courses/' . $context;
        }

        if ($object) {
            $compiledFilters['object-filter'] = "http://course-zone.org/expapi/courses/" . $object;
        }

        if ($context && $object) {
            $compiledFilters['object-filter'] = "http://course-zone.org/expapi/courses/section/" . $object;
        }

        return $compiledFilters;
    }

    /**
     * Extracting section ids from statements
     *
     * @return array $sectionIds
     */
    public static function getIdSections(array $statements): array
    {
        $sectionIds = [];

        foreach ($statements as $statement) {
            $content = $statement->content;
            $object = explode('/', $content->object->id);

            $sectionId = end($object);
            if (!in_array($sectionId, $sectionIds)) {
                $sectionIds[] = $sectionId;
            }
        }

        return $sectionIds;
    }
}
