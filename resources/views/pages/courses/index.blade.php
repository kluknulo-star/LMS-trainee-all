@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="courses">
    <div class="container">
        <div class="courses__title h1">
            Assigned courses
        </div>
        <div class="course__item-add">
            <img src="" alt="" class="courses__img">
            <div class="courses__img-blackout"></div>
            <div class="courses__course-title courses__course-add-title h2">Create</div>
            <button class="courses__course-add-button"><i class="fas fa-plus"></i></button>
        </div>
        <div class="courses__row">
            @for ($i = 1; $i < 5; $i++)
            <div class="courses__item">
                <img src="" alt="" class="courses__img">
                <div class="courses__img-blackout"></div>
                <div class="courses__course-title h2">Course PHP</div>
                <div class="courses__course-description"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. At consequatur eum harum illo quaerat, saepe voluptate. A cupiditate enim, eum exercitationem incidunt molestiae qui repudiandae tenetur ullam voluptatibus. Architecto, voluptatum? </div>
                <a href="" class="courses__course-play"><i class="fas fa-play"></i></a>
            </div>
            @endfor
        </div>
    </div>
{{--  Роут для play курса  --}}
{{--    {{ route('courses.play', ['id' => $course->course_id]) }}--}}
</div>

@component('components.footer')
@endcomponent
