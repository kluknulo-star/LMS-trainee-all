@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <img src="" alt="" class="courses__img">
    <div class="h3 mb20">Title: {{ $course->title }}</div>
    <div class="margin5">
        Author: <a href="{{ route('users.show', ['id' => $course->user()->value("user_id")]) }}">{{ $course->user()->value("email") }}</a>
    </div>
    <div class="courses__course-description mb30">Description: {{ Str::limit($course->description, 200, '...') }}</div>
    <div class="h3 mb20">Content:</div>
    {{var_dump($course->content)}}
</div>

@component('components.footer')
@endcomponent
