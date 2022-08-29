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
     * @return string|null $compiledStatement
     */
    public static function compileFilters(array $actors = [], array $verbs = [], array $objects = [], array $contexts = []): string|null
    {
        $compileFilters = [];
        if ($verbs) {
            array_walk($verbs, function (&$item) {
                $item = 'http://adlnet.gov/expapi/verbs/' . $item;
            });
            $compileFilters['verb-filter'] = $verbs;
        }

        if ($actors) {
            array_walk($actors, function (&$item) {
                $item = 'mailto:' . $item;
            });
            $compileFilters['actor-filter'] = $actors;
        }

        if ($contexts) {
            array_walk($contexts, function (&$item) {
                $item = 'http://course-zone.org/expapi/courses/' . $item;
            });
            $compileFilters['context-filter'] = $contexts;
        }

        if ($objects) {
            array_walk($objects, function (&$item) {
                $item = 'http://course-zone.org/expapi/courses/' . $item;
            });
            $compileFilters['object-filter'] = $objects;
        }

        if ($contexts && $objects) {
            array_walk($contexts, function (&$item) {
                $item = 'http://course-zone.org/expapi/courses/sections/' . $item;
            });
            $compileFilters['object-filter'] = $contexts;
        }

        $compiledFilters = ['filter-parameters' => $compileFilters];
        return json_encode($compiledFilters);
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
            $object = explode('/', $statement->object->id);

            $sectionId = end($object);
            if (!in_array($sectionId, $sectionIds)) {
                $sectionIds[] = $sectionId;
            }
        }

        return $sectionIds;
    }

    public static function getEmailUsers(array $statements) : array
    {
        $usersIds = [];

        foreach ($statements as $statement) {
            $actor = explode(':', $statement->actor->mbox);
            $usersId = end($actor);
            if (!in_array($usersId, $usersIds)) {
                $usersIds[] = $usersId;
            }
        }

        return $usersIds;
    }
}
