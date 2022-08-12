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
    <div class="h3 mb20">Course content:</div>
    <a href="" class="users__title-link">
        <i class="fa fa-arrow-left"></i>
    </a>
    <a href="" class="users__title-link">
        <i class="fa fa-arrow-right"></i>
    </a>
        @foreach(json_decode($course->content) as $element)
        <div class="margin20-0">
            <p><b>{{$element->type}}</b></p>
            <p>{{$element->content}}</p>
        </div>
        @endforeach
</div>

@component('components.footer')
@endcomponent
