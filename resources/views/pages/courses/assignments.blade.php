@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="courses">
    <div class="container">

        <div class="courses__title h1 flex flex-just-spbtw w1200">
            Assigned courses

            <form action="{{ route('courses.assignments') }}" method="get" class="courses__form-search">
                <input name="search" type="text" placeholder="Search course" class="courses__input-search">
                <button type="submit" class="courses__button-search"><i class="fas fa-search"></i></button>
            </form>

        </div>
        {{ $courses->withQueryString()->links() }}
        <div class="courses__row">
            @forelse ($courses as $key => $course)
                <div class="courses__item">
                    <img src="" alt="" class="courses__img">
                    <div class="courses__course-title h2 mb20">{{ $course->title }}</div>
                    <div class="courses__course-description mb30">{{ Str::limit($course->description, 200, '...') }}</div>
                    <div class="courses__course-author mb15">Author: <a href=""></a></div>
                    <div class="courses__course-assign-count"><i class="fa-solid fa-user"></i> 3000</div>
                    <a href="" class="courses__course-play"><i class="fas fa-play"></i></a>
                </div>
            @empty
                Assignment courses not found
            @endforelse
        </div>
    </div>
{{--  Роут для play курса  --}}
{{--    {{ route('courses.play', ['id' => $course->course_id]) }}--}}
</div>

@component('components.footer')
@endcomponent
