<?php

namespace App\Courses\Controllers;

use App\Courses\Services\ExportCourseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportCourseController extends Controller
{
    public function __construct(
        private ExportCourseService $exportCourseService,
    )
    {
    }

    public function export(string $type)
    {
        $this->exportCourseService->createExport($type);
        return redirect('/users/'.auth()->user()->user_id); // !
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
