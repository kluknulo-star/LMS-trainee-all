<?php

namespace Tests\Unit;

use App\Courses\Services\CourseService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateCourse()
    {
        $this->clearTestingData();
        $courseData = [
            'title' => 'Course Title',
            'description' => 'Course Description',
            'author_id' => 3,
        ];

        $courseService = app(CourseService::class);
        $courseService->store($courseData);

        $dbCourse = DB::table('courses')->where('title', 'Course Title')->first();
        $this->assertTrue($dbCourse->title == $courseData['title']);
    }

    public function testUpdateCourse()
    {
        $courseData = ['description' => 'qwertyuiop qwertyuiop qwertyuiop'];
        $dbCourse = DB::table('courses')->where('title', 'Course Title')->first();

        $courseService = app(CourseService::class);
        $courseService->update($dbCourse->course_id, $courseData);

        $dbCourse = DB::table('courses')->where('title', 'Course Title')->first();
        $this->assertTrue($dbCourse->description == $courseData['description']);
    }

    public function testSoftDeleteCourse()
    {
        $dbCourse = DB::table('courses')->select('course_id')->where('title', 'Course Title')->first();

        $courseService = app(CourseService::class);
        $result = $courseService->destroy($dbCourse->course_id);

        $this->assertTrue($result);
    }

    public function testRestoreCourse()
    {
        $dbCourse = DB::table('courses')->select('course_id')->where('title', 'Course Title')->first();

        $courseService = app(CourseService::class);
        $result = $courseService->restore($dbCourse->course_id);

        $this->assertTrue($result);
        $this->clearTestingData();
    }

    public function clearTestingData()
    {
        DB::table('courses')->where('title', 'Course Title')->delete();
    }
}
