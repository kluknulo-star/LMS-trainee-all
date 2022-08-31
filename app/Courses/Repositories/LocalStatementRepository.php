<?php

namespace App\Courses\Repositories;

use App\Courses\Models\Assignment;
use App\Courses\Models\ItemsStats;
use phpDocumentor\Reflection\Types\Collection;

class LocalStatementRepository
{
    public function createLocalStatement(int $userId, int $itemId, string $status): ItemsStats
    {
        return ItemsStats::firstOrCreate([
            'user_id' => $userId,
            'item_id' => $itemId,
            'status' => $status,
        ]);
    }

    public function searchAllLocalStatement(int $userId, int $courseId)
    {
        return ItemsStats::where('user_id', $userId)
            ->join('course_items', 'course_items_users_stats.item_id', '=', 'course_items.item_id')
            ->where('course_id', $courseId)
            ->where('course_items_users_stats.deleted_at', null)
            ->get(['course_items_users_stats.item_id', 'course_items_users_stats.status']);
    }

    public function searchPassedStatement(int $userId, int $courseId)
    {
        return ItemsStats::where('course_items_users_stats.status', 'passed')
            ->where('user_id', $userId)
            ->join('course_items', 'course_items_users_stats.item_id', '=', 'course_items.item_id')
            ->where('course_id', $courseId)
            ->where('deleted_at', null)
            ->get(['course_items_users_stats.item_id', 'course_items_users_stats.status']);
    }

    public function updateLocalProgress($userId, $courseId, $progress): Assignment
    {
        return Assignment::where('course_id', $courseId)
            ->where('student_id', $userId)
            ->update(['progress' => $progress]);
    }
}
