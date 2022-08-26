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
        $statementsLaunched =
            ItemsStats::where('status', 'launched')
            ->where('user_id', $userId)
            ->join('course_items', 'course_items_users_stats.item_id', '=', 'course_items.item_id')
            ->where('course_id', $courseId)
            ->get('course_items_users_stats.item_id');

        $statementsLaunchedNew = [];
        foreach ($statementsLaunched as $statement){
            $statementsLaunchedNew[] = $statement->item_id;
        }

        $statementsPassed =
            ItemsStats::where('status', 'passed')
                ->where('user_id', $userId)
                ->join('course_items', 'course_items_users_stats.item_id', '=', 'course_items.item_id')
                ->where('course_id', $courseId)
                ->get('course_items_users_stats.item_id');

        $statementsPassedNew = [];
        foreach ($statementsPassed as $statement){
            $statementsPassedNew[] = $statement->item_id;
        }

        $progress = (int)round(count($statementsPassedNew) / $allContentCount * 100);

        Assignment::where('student_id', $userId)
            ->where('course_id', $courseId)
            ->update(['progress' => $progress]);

        $statements = [
            'launched' => $statementsLaunchedNew,
            'passed' => $statementsPassedNew,
            'progress' => $progress,
        ];

        return $statements;
    }
}
