<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Assignment;
use App\Courses\Models\ItemsStats;

class LocalStatements
{
    public function sendLocalStatement(int $userId, int $itemId, string $status): bool
    {
        if(!empty($userId) && !empty($itemId) && !empty($status)) {
            ItemsStats::firstOrCreate([
                'user_id' => $userId,
                'item_id' => $itemId,
                'status' => $status,
            ]);
            return true;
        }
        return false;
    }

    public function getProgressStudent(int $userId, int $courseId, int $allContentCount): array
    {
        $statements =
            ItemsStats::where('user_id', $userId)
            ->join('course_items', 'course_items_users_stats.item_id', '=', 'course_items.item_id')
            ->where('course_id', $courseId)
            ->get(['course_items_users_stats.item_id', 'course_items_users_stats.status']);

        $statementsLaunched = $statements->where('status', 'launched')->pluck('item_id')->ToArray();
        $statementsPassed = $statements->where('status', 'passed')->pluck('item_id')->ToArray();

        $progress = 0;
        if(count($statementsPassed) > 0) {
            $progress = (int)round(count($statementsPassed) / $allContentCount * 100);
        }

        $statements = [
            'launched' => $statementsLaunched,
            'passed' => $statementsPassed,
            'progress' => $progress,
        ];

        return $statements;
    }

    public function setProgress(int $userId, int $courseId, int $contentCount)
    {
        $statementsPassed =
            ItemsStats::where('course_items_users_stats.status', 'passed')
                ->where('user_id', $userId)
                ->join('course_items', 'course_items_users_stats.item_id', '=', 'course_items.item_id')
                ->where('course_id', $courseId)
                ->get(['course_items_users_stats.item_id', 'course_items_users_stats.status']);

        $progress = 0;
        if(count($statementsPassed) > 0) {
            $progress = (int)round(count($statementsPassed) / $contentCount * 100);
        }

        if(!empty($userId) && !empty($courseId) && $progress >= 0) {
            $this->sendProgress($userId, $courseId, $progress);
            return true;
        }
        return false;
    }

    public function sendProgress(int $userId, int $courseId, int $progress)
    {
        Assignment::where('course_id', $courseId)
            ->where('student_id', $userId)
            ->update(['progress' => $progress]);

        return true;
    }
}
