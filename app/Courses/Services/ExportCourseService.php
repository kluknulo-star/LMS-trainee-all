<?php

namespace App\Courses\Services;

use App\Courses\Models\Export;
use App\Courses\Repositories\CourseRepository;
use App\Users\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportCourseService extends CourseService
{
    public function __construct(
        private UserService $userService,
        private CourseService $courseService,
        private CourseRepository $courseRepository,
    )
    {
    }

    public function getExports($userId): Collection
    {
        return Export::where('export_owner_id', $userId)->orderBy('export_id', 'DESC')->get();
    }

    public function findExcelFile($id): Collection
    {
        return Export::where('export_id', $id)->get();
    }

    public function createExport(string $type): bool
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        switch ($type) {
            case 'all':
                $teachers = $this->userService->getTeachers()->get();
                $courses = $this->courseRepository->getAll();
                $fileName = now().'-all-excel.xlsx';
                break;
            case 'own':
                $teachers = auth()->user();
                $courses = $this->courseRepository->getOwnCourses('')->get();
                $fileName = now().'-own-excel.xlsx';
                break;
        }

        $countFilteredData = count($courses);

        for ($i = 0; $i < $countFilteredData; $i++) {
            $sheet->setCellValue('A' . ($i + 1), $courses[$i]->course_id);
            $sheet->setCellValue('B' . ($i + 1), $courses[$i]->title);
            $sheet->setCellValue('C' . ($i + 1), $courses[$i]->assigned_users_count);
            $sheet->setCellValue('D' . ($i + 1), $teachers->where('user_id', $courses[$i]->author_id)->value('user_id'));
            $sheet->setCellValue('E' . ($i + 1), $teachers->where('user_id', $courses[$i]->author_id)->value('name'));
            $sheet->setCellValue('F' . ($i + 1), $teachers->where('user_id', $courses[$i]->author_id)->value('email'));
        }

        $writer = new Xlsx($spreadsheet);
        $savedFileName = '../storage/app/'.$fileName;
        $writer->save($savedFileName);

        $export = new Export();
        $export->export_owner_id = auth()->user()->user_id;
        $export->export_file_path = $fileName;
        return $export->save();
    }
}
