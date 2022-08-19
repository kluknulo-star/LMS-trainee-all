<?php

namespace App\Courses\Services;

use App\Courses\Models\Export;

class ExportCourseService extends CourseService
{
    public function getExports($userId)
    {
        return Export::where('export_owner_id', $userId)->get();
    }

    public function findExcelFile($id)
    {
        return Export::where('export_id', $id)->get();
    }
}
