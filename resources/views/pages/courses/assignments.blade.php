@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="courses">
    <div class="container">
        @if (!empty(session()->get('success')))
            <div class="success">{{ session()->get('success') }}</div>
        @endif
        <div class="courses__title h1 flex flex-just-spbtw w1200">
            {{ __('main.assigned') }} {{ __('main.courses') }}

            <form action="{{ route('courses.assignments') }}" method="get" class="courses__form-search">
                <input name="search" type="text" placeholder="{{ __('main.search') }}" class="courses__input-search">
                <button type="submit" class="courses__button-search"><i class="fas fa-search"></i></button>
            </form>

        </div>
        {{ $courses->withQueryString()->links() }}
        <div class="courses__row">
            @forelse ($courses as $key => $course)
                <div class="courses__item">
                    <img src="" alt="" class="courses__img">
                    <div class="courses__course-title h3 mb20">{{ $course->title }}</div>
                    <div
                        class="courses__course-description mb30">{{ Str::limit($course->description, 100, '...') }}</div>
                    <div class="courses__course-author">{{ __('main.author') }}: <a
                            href="{{ route('users.show', ['id' => $course->author_id]) }}">{{ $course->author->email }}</a>
                    </div>
                    <div class="courses__course-assign-count"><i class="fa-solid fa-user"></i> {{ $course->assigned_users_count }}</div>
                    <a href="{{ route('courses.play', ['id' => $course->course_id]) }}" class="courses__course-play"><i class="fas fa-play"></i></a>
                </div>
            @empty
                {{ __('main.assigned') }} {{ __('main.courses') }} {{ __('main.notFound') }}
            @endforelse
        </div>
    </div>
{{--  Роут для play курса  --}}
{{--    {{ route('courses.play', ['id' => $course->course_id]) }}--}}
</div>

@component('components.footer')
@endcomponent
