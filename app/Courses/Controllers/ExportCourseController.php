<?php

namespace App\Courses\Controllers;

use App\Courses\Controllers\CourseController;
use App\Courses\Models\Export;
use App\Courses\Services\CourseService;
use App\Courses\Services\ExportCourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportCourseController extends CourseController
{
    public function __construct(private ExportCourseService $exportCourseService)
    {
    }

    public function export(string $type)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($type === 'all') {
            $courses = (new CourseService())->getAll();
            $filteredData = $courses;
            $fileName = now().'-all-excel.xlsx';
        } else {
            $courses = (new CourseService())->getOwn();
            $filteredData = $courses->get();
            $fileName = now().'-own-excel.xlsx';
        }

        for ($i = 0; $i < count($filteredData); $i++) {
            $sheet->setCellValue('A' . ($i + 1), $filteredData[$i]->course_id);
            $sheet->setCellValue('B' . ($i + 1), $filteredData[$i]->title);
        }

        $writer = new Xlsx($spreadsheet);
        $savedFileName = '../storage/app/'.$fileName;
        $writer->save($savedFileName);

        $export = new Export();
        $export->export_owner_id = auth()->user()->user_id;
        $export->export_file_path = $fileName;
        $export->save();

        return redirect('/users/'.auth()->user()->user_id);
    }

    public function exportDownload(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $exportFile = $this->exportCourseService->findExcelFile($request->id)->toArray();

        if ($exportFile) {
            $file = array_shift($exportFile);

            if (file_exists('../storage/app/' . $file['export_file_path'])) {
                return Storage::download($file['export_file_path']);
            }
        }

        return abort('403');
    }
}
