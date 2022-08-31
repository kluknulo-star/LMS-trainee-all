<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Assignment;
use App\Courses\Repositories\LocalStatementRepository;

class LocalStatements
{
    private LocalStatementRepository $localStatementRepository;

    public function __construct()
    {
        $this->localStatementRepository = new LocalStatementRepository();
    }

    public function sendLocalStatement(int $userId, int $itemId, string $status): bool
    {
        if(!empty($userId) && !empty($itemId) && !empty($status)) {
            $this->localStatementRepository->createLocalStatement($userId, $itemId, $status);
            return true;
        }
        return false;
    }

    public function getProgressStudent(int $userId, int $courseId, int $allContentCount): array
    {
        $statements = $this->localStatementRepository->searchAllLocalStatement($userId, $courseId);

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
        $statementsPassed = $this->localStatementRepository->searchPassedStatement($userId, $courseId);

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

    public function sendProgress(int $userId, int $courseId, int $progress): Assignment
    {
        return $this->localStatementRepository->updateLocalProgress($userId, $courseId, $progress);
    }
}
