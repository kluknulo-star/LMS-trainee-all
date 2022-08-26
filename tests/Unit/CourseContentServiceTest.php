<?php

namespace Tests\Unit;

use App\Courses\Services\CourseContentService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CourseContentServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateCourseContent()
    {
        $this->clearTestingData();
        $courseContentData = [
            'sectionTitle' => 'CourseContentTitle',
            'sectionContent' => 'Course Item Content',
            'course_id' => 3,
            'sectionType' => 1,
        ];

        $courseContentService = app(CourseContentService::class);
        $courseContentService->store($courseContentData, $courseContentData['course_id']);

        $dbContentCourse = DB::table('course_items')->where('title', 'CourseContentTitle')->first();
        $this->assertTrue($dbContentCourse->title == $courseContentData['sectionTitle']);
    }

    public function testUpdateCourse()
    {
        $courseContentData = [
            'sectionTitle' => 'CourseContentTitle',
            'sectionContent' => 'qwertyuiop qwertyuiop qwertyuiop',
            'course_id' => 3,
            'sectionType' => 1,
        ];
        $dbContentCourse = DB::table('course_items')->where('title', 'CourseContentTitle')->first();

        $courseContentService = app(CourseContentService::class);
        $courseContentService->update($courseContentData, $dbContentCourse->item_id);

        $dbContentCourse = DB::table('course_items')->where('title', 'CourseContentTitle')->first();
        $this->assertTrue(json_decode($dbContentCourse->item_content) == $courseContentData['sectionContent']);
    }

    public function testSoftDeleteCourse()
    {
        $dbContentCourse = DB::table('course_items')->select('item_id')->where('title', 'CourseContentTitle')->first();

        $courseContentService = app(CourseContentService::class);
        $result = $courseContentService->destroy($dbContentCourse->item_id);

        $this->assertTrue($result);
    }

    public function testRestoreCourse()
    {
        $dbContentCourse = DB::table('course_items')->select('item_id')->where('title', 'CourseContentTitle')->first();
        dump($dbContentCourse);
        $courseContentService = app(CourseContentService::class);
        $result = $courseContentService->restore($dbContentCourse->item_id);

        $this->assertTrue($result);
        $this->clearTestingData();
    }

    public function clearTestingData()
    {
        DB::table('course_items')->where('title', 'CourseContentTitle')->delete();
    }
}
