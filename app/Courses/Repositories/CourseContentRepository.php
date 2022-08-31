<?php

namespace App\Courses\Repositories;

use App\Courses\Models\CourseItems;
use App\Courses\Models\ItemsStats;

class CourseContentRepository
{
    public function update($sectionId, $courseContent): bool
    {
        return CourseItems::where('item_id', $sectionId)->update($courseContent);
    }

    public function create($courseContent): CourseItems
    {
        return CourseItems::create($courseContent);
    }

    public function destroy($sectionId): bool
    {
        $this->destroyStatement($sectionId);
        return CourseItems::findOrFail($sectionId)->delete();
    }

    public function destroyStatement($sectionId)
    {
        return ItemsStats::where('item_id', $sectionId)->firstOrFail()->delete();
    }

    public function restore($sectionId): bool
    {
        $this->restoreStatement($sectionId);
        return CourseItems::where([['item_id', '=', $sectionId]])->restore();
    }

    public function restoreStatement($sectionId)
    {
        return ItemsStats::where('item_id', $sectionId)->firstOrFail()->restore();
    }
}
