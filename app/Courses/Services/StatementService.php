<?php

namespace App\Courses\Services;

use App\Courses\Helpers\ClientLRS;

class StatementService
{
    public function getSection(mixed $allCourseContent, int $sectionId): mixed
    {
        $section = [];

        foreach ($allCourseContent as $element) {
            if ($element->item_id == $sectionId) {
                $section = $element;
                break;
            }
        }
        return $section;
    }

    public function getStudentProgress(int $courseId, string $email) : array
    {
        return ClientLRS::getProgressStudent($email, $courseId);
    }
}
