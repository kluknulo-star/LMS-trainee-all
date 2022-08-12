<aside class="aside">
    <div class="aside__header">
        <div class="aside__logo logo h2">CourseZone</div>

        @if (auth()->user()->is_teacher == 1)
            <a href="{{ route('users') }}" class="aside__link button">Students</a>
            <a href="{{ route('courses.own') }}" class="aside__link button">My courses</a>
        @endif

        <a href="{{ route('courses.assignments') }}" class="aside__link button">Assigned courses</a>
        <a href="{{ route('about') }}" class="aside__link button">About us</a>
        {{--        <a href="" class="aside__link button">About us</a>--}}
    </div>

    <div class="aside__footer">
        @auth
            <a href="{{ route('users.show', ['id' => auth()->user()->user_id]) }}" class="aside__link aside__link_profile">{{ auth()->user()->email }}</a>
            <a href="{{ route('logout') }}" class="aside__link button">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
        @endauth
    </div>
</aside>
