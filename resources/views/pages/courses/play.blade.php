@component('components.header')
@endcomponent

@component('components.aside')
@endcomponent

<div class="container">
    <img src="" alt="" class="courses__img">
    <div class="h3 mb20">Title: {{ $course->title }}</div>
    <div class="margin5">
        Author: <a
            href="{{ route('users.show', ['id' => $course->user()->value("user_id")]) }}">{{ $course->user()->value("email") }}</a>
    </div>
    <div class="courses__course-description mb30">Description: {{ Str::limit($course->description, 200, '...') }}</div>
    <div class="h3 mb20">Course content:</div>
    @foreach(json_decode($course->content) as $element)
        <div class="margin20-0">
            <b>{{$element->title}}</b>
            ❮{{$element->type}}❯
            <form class="table-action-form"
                  target="_blank"
                  action="{{ route('send.launched.statement', ['course_id' => $course->course_id, 'section_id' => $element->section_id]) }}"
                  method="post">
                @csrf
                @method('post')
                <button type="submit" class="table-action-button table-show-button"><i class="fas fa-check"></i>
                </button>
            </form>
            <form class="table-action-form"
                  target="_blank"
                  action="{{ route('send.passed.statement', ['course_id' => $course->course_id, 'section_id' => $element->section_id]) }}"
                  method="post">
                @csrf
                @method('post')
                <button type="submit" class="table-action-button table-show-button"><i class="fas fa-check-double"></i>
                </button>
            </form>
            <p>{{$element->content ?? ""}}
            </p>
            <br>

        </div>
    @endforeach
</div>

@component('components.footer')
@endcomponent
