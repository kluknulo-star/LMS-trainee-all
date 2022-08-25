<?php

namespace App\Courses\Helpers;

use App\Courses\Models\ItemsStats;

class StatementLocalSend
{
    public function sendLocalStatement(int $userId, int $itemId, string $status): bool
    {
        if(!empty($userId) && !empty($itemId) && !empty($status)) {
            $stat = ItemsStats::firstOrCreate([
                'user_id' => $userId,
                'item_id' => $itemId,
                'status' => $status,
            ]);
            return true;
        }
        return false;
    }
}
