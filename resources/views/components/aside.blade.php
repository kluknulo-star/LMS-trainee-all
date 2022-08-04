<aside class="aside">
    <div class="aside__header">
        <div class="aside__logo logo h2">CourseZone</div>
        <a href="{{ url('/users') }}" class="aside__link button">Students</a>
        <a href="{{ url('/courses') }}" class="aside__link button">Assigned courses</a>
        <a href="{{ url('/courses/my') }}" class="aside__link button">My courses</a>
    </div>

{{--    @if($page == 'Assigned courses') aside__current @endif--}}

    <div class="aside__footer">
        <a href="{{ url('/users/'.'1') }}" class="aside__link aside__link_profile">mail@email.com</a>
        <a href="{{ url('/login') }}" class="aside__link button">Logout</a>
    </div>
</aside>
