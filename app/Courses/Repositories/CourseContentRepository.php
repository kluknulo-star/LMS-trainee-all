<?php

namespace App\Courses\Repositories;

use App\Courses\Models\CourseItems;

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
        return CourseItems::findOrFail($sectionId)->delete();
    }

    public function restore($sectionId): bool
    {
        return CourseItems::where([['item_id', '=', $sectionId]])->restore();
    }
}
