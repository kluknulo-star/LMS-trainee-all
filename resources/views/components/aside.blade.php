<aside class="aside">
    <div class="aside__header">
        <a href="{{ route('users') }}" class="aside__logo whitesmoke-text logo h2">CourseZone</a>

        @if (auth()->user()->is_teacher == 1)
            <a href="{{ route('users') }}" class="aside__link button">{{ trans('main.students') }}</a>
            <a href="{{ route('courses.own') }}" class="aside__link button">{{ trans('main.myCourses') }}</a>
        @endif

        <a href="{{ route('courses.assignments') }}" class="aside__link button">{{ trans('main.assignedCourses') }}</a>
    </div>

    <div class="aside__footer">
        @auth
            <a href="{{ route('users.show', ['id' => auth()->user()->user_id]) }}" class="aside__link aside__link_profile">{{ auth()->user()->email }}</a>
            <a href="{{ route('logout') }}" class="aside__link button">{{ trans('main.logout') }} <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
        @endauth
    </div>
</aside>
