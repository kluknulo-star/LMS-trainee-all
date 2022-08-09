@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="courses">
    <div class="container">
        <div class="courses__title h1">
            My courses
        </div>
        <div class="course__item-add">
            <img src="" alt="" class="courses__img">
            <div class="courses__valign-text">Create</div>
            <button class="courses__course-add-button"><i class="fas fa-plus"></i></button>
        </div>
        <div class="courses__row">
            @foreach ($courses as $key => $course)
                <div class="courses__item">
                    <img src="" alt="" class="courses__img">
                    <div class="courses__img-blackout"></div>
                    <div class="courses__course-title h2">{{ $course->title }}</div>
                    <div class="courses__course-description mb30">{{ $course->description }}</div>
                    <div class="courses__course-author mb15">Author: <a href="">{{ auth()->user()->name }}</a></div>
                    <div class="courses__course-assign-count"><i class="fa-solid fa-user"></i> {{ mt_rand(1000, 10000) }}</div>
                    <a href="" class="courses__course-edit"><i class="fas fa-pen"></i></a>
                    <a href="" class="courses__course-play"><i class="fas fa-play"></i></a>
                </div>
            @endforeach
        </div>
    </div>
    {{--  Роут для play курса  --}}
    {{--    {{ route('courses.play', ['id' => $course->course_id]) }}--}}
</div>

@component('components.footer')
@endcomponent
