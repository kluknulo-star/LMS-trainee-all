<aside class="aside">
    <div class="aside__header">
        <div class="aside__logo logo h2">CourseZone</div>
        <a href="{{ url('/users') }}" class="aside__link button">Students</a>
        <a href="{{ url('/courses') }}" class="aside__link button">Assigned courses</a>
        <a href="{{ url('/courses/my') }}" class="aside__link button">My courses</a>
    </div>

{{--    @if($page == 'Assigned courses') aside__current @endif--}}

    <div class="aside__footer">
        @auth
            <a href="{{ url('/users/'.auth()->user()->user_id) }}" class="aside__link aside__link_profile">{{ auth()->user()->email }}</a>
            <a href="{{ url('/logout') }}" class="aside__link button">Logout</a>
        @endauth
    </div>
</aside>
